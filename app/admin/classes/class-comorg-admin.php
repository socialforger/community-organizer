<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Admin Loader
 *
 * Carica menu, settings e wizard.
 */
class ComOrg_Admin {

    /**
     * Singleton
     */
    protected static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->includes();
        $this->hooks();
    }

    /**
     * Include file admin
     */
    private function includes() {
        require_once COMORG_PLUGIN_DIR . 'app/admin/classes/class-comorg-admin-menu.php';
        require_once COMORG_PLUGIN_DIR . 'app/admin/classes/class-comorg-admin-settings.php';
        require_once COMORG_PLUGIN_DIR . 'app/admin/classes/class-comorg-admin-wizard.php';
    }

    /**
     * Hook admin
     */
    private function hooks() {
        add_action( 'admin_menu', array( 'ComOrg_Admin_Menu', 'register_menu' ) );
    }
}
