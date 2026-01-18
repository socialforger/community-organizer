<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Auth (Magic Link)
 */
class ComOrg_Auth {

    const TOKEN_META_KEY = '_comorg_magic_token';
    const TOKEN_EXP_META_KEY = '_comorg_magic_token_exp';

    public static function init() {
        add_shortcode( 'comorg_register', array( __CLASS__, 'render_register_form' ) );
        add_shortcode( 'comorg_login', array( __CLASS__, 'render_login_form' ) );

        add_action( 'init', array( __CLASS__, 'handle_magic_link' ) );
        add_action( 'admin_post_nopriv_comorg_request_magic_link', array( __CLASS__, 'handle_request_magic_link' ) );
        add_action( 'admin_post_nopriv_comorg_request_magic_link_login', array( __CLASS__, 'handle_request_magic_link_login' ) );
    }

    /**
     * Form registrazione (solo email)
     */
    public static function render_register_form() {
        ob_start();
        ?>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="comorg_request_magic_link">
            <p>
                <label for="comorg_email"><?php _e( 'Inserisci la tua email', 'comorg' ); ?></label><br>
                <input type="email" name="comorg_email" id="comorg_email" required>
            </p>
            <p>
                <button type="submit"><?php _e( 'Registrati / Continua', 'comorg' ); ?></button>
            </p>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Form accesso (solo email)
     */
    public static function render_login_form() {
        ob_start();
        ?>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="comorg_request_magic_link_login">
            <p>
                <label for="comorg_email_login"><?php _e( 'Inserisci la tua email', 'comorg' ); ?></label><br>
                <input type="email" name="comorg_email" id="comorg_email_login" required>
            </p>
            <p>
                <button type="submit"><?php _e( 'Accedi / Continua', 'comorg' ); ?></button>
            </p>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Gestisce richiesta magic link (registrazione)
     */
    public static function handle_request_magic_link() {
        self::process_magic_link_request( 'register' );
    }

    /**
     * Gestisce richiesta magic link (login)
     */
    public static function handle_request_magic_link_login() {
        self::process_magic_link_request( 'login' );
    }

    protected static function process_magic_link_request( $mode = 'register' ) {
        if ( empty( $_POST['comorg_email'] ) || ! is_email( $_POST['comorg_email'] ) ) {
            wp_die( __( 'Email non valida.', 'comorg' ) );
        }

        $email = sanitize_email( $_POST['comorg_email'] );
        $user  = get_user_by( 'email', $email );

        if ( ! $user && $mode === 'login' ) {
            // login: se non esiste, creiamo comunque l’utente
            $user = self::create_user_from_email( $email );
        } elseif ( ! $user && $mode === 'register' ) {
            $user = self::create_user_from_email( $email );
        }

        if ( ! $user ) {
            wp_die( __( 'Impossibile creare o trovare l’utente.', 'comorg' ) );
        }

        $token = wp_generate_password( 32, false );
        $exp   = time() + HOUR_IN_SECONDS;

        update_user_meta( $user->ID, self::TOKEN_META_KEY, $token );
        update_user_meta( $user->ID, self::TOKEN_EXP_META_KEY, $exp );

        $magic_url = add_query_arg( array(
            'comorg_magic' => 1,
            'uid'          => $user->ID,
            'token'        => $token,
        ), home_url( '/' ) );

        $subject = __( 'Accedi a Community Organizer', 'comorg' );
        $message = sprintf(
            __( "Clicca qui per accedere:\n\n%s\n\nIl link scade tra 1 ora.", 'comorg' ),
            $magic_url
        );

        wp_mail( $email, $subject, $message );

        wp_redirect( add_query_arg( 'magic_sent', 1, wp_get_referer() ?: home_url( '/' ) ) );
        exit;
    }

    protected static function create_user_from_email( $email ) {
        $username = 'comorg_' . wp_generate_password( 8, false, false );
        $password = wp_generate_password( 20, true, true );

        $user_id = wp_create_user( $username, $password, $email );

        if ( is_wp_error( $user_id ) ) {
            return false;
        }

        // opzionale: ruolo base
        wp_update_user( array(
            'ID'   => $user_id,
            'role' => 'subscriber',
        ) );

        return get_user_by( 'id', $user_id );
    }

    /**
     * Gestione magic link
     */
    public static function handle_magic_link() {
        if ( empty( $_GET['comorg_magic'] ) || empty( $_GET['uid'] ) || empty( $_GET['token'] ) ) {
            return;
        }

        $user_id = absint( $_GET['uid'] );
        $token   = sanitize_text_field( $_GET['token'] );

        $saved_token = get_user_meta( $user_id, self::TOKEN_META_KEY, true );
        $exp         = (int) get_user_meta( $user_id, self::TOKEN_EXP_META_KEY, true );

        if ( ! $saved_token || $saved_token !== $token || time() > $exp ) {
            wp_die( __( 'Link non valido o scaduto.', 'comorg' ) );
        }

        // login
        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id, true );

        // invalida token
        delete_user_meta( $user_id, self::TOKEN_META_KEY );
        delete_user_meta( $user_id, self::TOKEN_EXP_META_KEY );

        // redirect a completamento profilo BuddyBoss
        $profile_url = function_exists( 'bp_core_get_user_domain' )
            ? bp_core_get_user_domain( $user_id ) . 'profile/edit/'
            : admin_url( 'profile.php' );

        wp_redirect( $profile_url );
        exit;
    }
}
