<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Admin Menu
 */
class ComOrg_Admin_Menu {

    public static function register_menu() {

        add_menu_page(
            __( 'ComOrg', 'comorg' ),
            __( 'ComOrg', 'comorg' ),
            'manage_options',
            'comorg-dashboard',
            array( __CLASS__, 'dashboard_page' ),
            'dashicons-networking',
            56
        );

        add_submenu_page(
            'comorg-dashboard',
            __( 'Dashboard', 'comorg' ),
            __( 'Dashboard', 'comorg' ),
            'manage_options',
            'comorg-dashboard',
            array( __CLASS__, 'dashboard_page' )
        );

        add_submenu_page(
            'comorg-dashboard',
            __( 'Setup Wizard', 'comorg' ),
            __( 'Setup Wizard', 'comorg' ),
            'manage_options',
            'comorg-wizard',
            array( 'ComOrg_Admin_Wizard', 'render' )
        );

        add_submenu_page(
            'comorg-dashboard',
            __( 'Settings', 'comorg' ),
            __( 'Settings', 'comorg' ),
            'manage_options',
            'comorg-settings',
            array( 'ComOrg_Admin_Settings', 'render' )
        );
    }

    public static function dashboard_page() {
        include COMORG_PLUGIN_DIR . 'app/admin/screens/dashboard.php';
    }
}
