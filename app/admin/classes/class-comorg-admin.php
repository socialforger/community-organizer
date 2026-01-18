<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Admin Menu
 */
class ComOrg_Admin {

    public static function init() {

        add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
    }

    public static function register_menu() {

        add_menu_page(
            __( 'ComOrg', 'comorg' ),
            __( 'ComOrg', 'comorg' ),
            'manage_options',
            'comorg',
            array( __CLASS__, 'render_dashboard' ),
            'dashicons-admin-generic',
            58
        );

        add_submenu_page(
            'comorg',
            __( 'Onboarding dinamico', 'comorg' ),
            __( 'Onboarding dinamico', 'comorg' ),
            'manage_options',
            'comorg-onboarding',
            array( 'ComOrg_Admin_Onboarding', 'render_page' )
        );
    }

    public static function render_dashboard() {
        echo '<div class="wrap"><h1>Community Organizer</h1></div>';
    }
}
