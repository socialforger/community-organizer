<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Admin Wizard
 *
 * Gestisce il wizard di configurazione iniziale.
 */
class ComOrg_Admin_Wizard {

    public static function init() {

        add_action( 'admin_init', array( __CLASS__, 'maybe_redirect_to_wizard' ) );
    }


    /**
     * Se necessario, reindirizza al wizard dopo attivazione plugin.
     */
    public static function maybe_redirect_to_wizard() {

        if ( get_option( 'comorg_run_wizard', false ) ) {

            delete_option( 'comorg_run_wizard' );

            wp_safe_redirect( admin_url( 'admin.php?page=comorg-wizard' ) );
            exit;
        }
    }


    /**
     * Render della pagina wizard.
     */
    public static function render_page() {

        include __DIR__ . '/../screens/wizard.php';
    }
}
