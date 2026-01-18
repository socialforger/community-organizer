<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Auth (Magic Link)
 *
 * Gestisce:
 * - Registrazione con solo email
 * - Accesso con solo email
 * - Magic link
 * - Creazione utente con username random
 * - Redirect al profilo BuddyBoss
 */
class ComOrg_Auth {

    const TOKEN_META_KEY     = '_comorg_magic_token';
    const TOKEN_TIME_META_KEY = '_comorg_magic_token_time';

    public static function init() {

        // Shortcodes
        add_shortcode( 'comorg_register', array( __CLASS__, 'render_register_form' ) );
        add_shortcode( 'comorg_login', array( __CLASS__, 'render_login_form' ) );

        // Form handlers
        add_action( 'admin_post_nopriv_comorg_request_magic_link', array( __CLASS__, 'handle_magic_link_request' ) );
        add_action( 'admin_post_nopriv_comorg_request_magic_link_login', array( __CLASS__, 'handle_magic_link_request' ) );

        // Magic link handler
        add_action( 'init', array( __CLASS__, 'process_magic_link' ) );
    }


    /**
     * ------------------------------------------------------------
     * 1. FORM FRONTEND
     * ------------------------------------------------------------
     */

    public static function render_register_form() {
        ob_start(); ?>
        
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="comorg_request_magic_link">
            <p>
                <label for="comorg_email"><?php _e( 'Inserisci la tua email', 'comorg' ); ?></label><br>
                <input type="email" name="comorg_email" id="comorg_email" required>
            </p>
            <button type="submit"><?php _e( 'Registrati / Continua', 'comorg' ); ?></button>
        </form>

        <?php return ob_get_clean();
    }


    public static function render_login_form() {
        ob_start(); ?>
        
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="comorg_request_magic_link_login">
            <p>
                <label for="comorg_email_login"><?php _e( 'Inserisci la tua email', 'comorg' ); ?></label><br>
                <input type="email" name="comorg_email" id="comorg_email_login" required>
            </p>
            <button type="submit"><?php _e( 'Accedi / Continua', 'comorg' ); ?></button>
        </form>

        <?php return ob_get_clean();
    }


    /**
     * ------------------------------------------------------------
     * 2. RICHIESTA MAGIC LINK
     * ------------------------------------------------------------
     */

    public static function handle_magic_link_request() {

        if ( empty( $_POST['comorg_email'] ) ) {
            wp_die( __( 'Email mancante.', 'comorg' ) );
        }

        $email = sanitize_email( $_POST['comorg_email'] );

        if ( ! comorg_is_valid_email( $email ) ) {
            wp_die( __( 'Email non valida.', 'comorg' ) );
        }

        // Trova o crea utente
        $user = get_user_by( 'email', $email );

        if ( ! $user ) {
            $user = self::create_user_from_email( $email );
        }

        if ( ! $user ) {
            wp_die( __( 'Errore nella creazione dell’utente.', 'comorg' ) );
        }

        // Genera token sicuro
        $token = comorg_generate_token();
        $time  = time();

        update_user_meta( $user->ID, self::TOKEN_META_KEY, $token );
        update_user_meta( $user->ID, self::TOKEN_TIME_META_KEY, $time );

        // Costruisci magic link
        $magic_url = add_query_arg( array(
            'comorg_magic' => 1,
            'uid'          => $user->ID,
            'token'        => $token,
        ), home_url( '/' ) );

        // Invia email
        wp_mail(
            $email,
            __( 'Accedi a Community Organizer', 'comorg' ),
            sprintf(
                __( "Clicca per accedere:\n\n%s\n\nIl link scade tra 1 ora.", 'comorg' ),
                $magic_url
            )
        );

        comorg_safe_redirect( add_query_arg( 'magic_sent', 1, wp_get_referer() ?: home_url() ) );
    }


    /**
     * ------------------------------------------------------------
     * 3. CREAZIONE UTENTE
     * ------------------------------------------------------------
     */

    protected static function create_user_from_email( $email ) {

        $username = comorg_generate_username();
        $password = wp_generate_password( 20, true, true );

        $user_id = wp_create_user( $username, $password, $email );

        if ( is_wp_error( $user_id ) ) {
            return false;
        }

        return get_user_by( 'id', $user_id );
    }


    /**
     * ------------------------------------------------------------
     * 4. PROCESSO MAGIC LINK
     * ------------------------------------------------------------
     */

    public static function process_magic_link() {

        if ( empty( $_GET['comorg_magic'] ) || empty( $_GET['uid'] ) || empty( $_GET['token'] ) ) {
            return;
        }

        $user_id = absint( $_GET['uid'] );
        $token   = sanitize_text_field( $_GET['token'] );

        $saved_token = get_user_meta( $user_id, self::TOKEN_META_KEY, true );
        $saved_time  = (int) get_user_meta( $user_id, self::TOKEN_TIME_META_KEY, true );

        // Token non valido
        if ( ! $saved_token || $saved_token !== $token ) {
            wp_die( __( 'Link non valido.', 'comorg' ) );
        }

        // Token scaduto
        if ( comorg_token_expired( $saved_time ) ) {
            wp_die( __( 'Link scaduto.', 'comorg' ) );
        }

        // Login
        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id, true );

        // Invalida token
        delete_user_meta( $user_id, self::TOKEN_META_KEY );
        delete_user_meta( $user_id, self::TOKEN_TIME_META_KEY );

        // Redirect al profilo BuddyBoss
        $url = comorg_get_profile_edit_url( $user_id );
        comorg_safe_redirect( $url );
    }
}
