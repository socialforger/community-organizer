<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Admin Menu
 *
 * Registra:
 * - Dashboard
 * - Wizard
 * - Onboarding dinamico
 */
class ComOrg_Admin_Menu {

    public static function init() {

        add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
    }


    public static function register_menu() {

        /**
         * Menu principale ComOrg
         */
        add_menu_page(
            __( 'ComOrg', 'comorg' ),
            __( 'ComOrg', 'comorg' ),
            'manage_options',
            'comorg',
            array( __CLASS__, 'render_dashboard' ),
            'dashicons-admin-generic',
            58
        );

        /**
         * Dashboard
         */
        add_submenu_page(
            'comorg',
            __( 'Dashboard', 'comorg' ),
            __( 'Dashboard', 'comorg' ),
            'manage_options',
            'comorg',
            array( __CLASS__, 'render_dashboard' )
        );

        /**
         * Wizard di configurazione
         */
        add_submenu_page(
            'comorg',
            __( 'Wizard', 'comorg' ),
            __( 'Wizard', 'comorg' ),
            'manage_options',
            'comorg-wizard',
            array( 'ComOrg_Admin_Wizard', 'render_page' )
        );

        /**
         * Onboarding dinamico
         */
        add_submenu_page(
            'comorg',
            __( 'Onboarding dinamico', 'comorg' ),
            __( 'Onboarding dinamico', 'comorg' ),
            'manage_options',
            'comorg-onboarding',
            array( 'ComOrg_Admin_Onboarding', 'render_page' )
        );
    }


    /**
     * Dashboard principale
     */
    public static function render_dashboard() {

        include __DIR__ . '/../screens/dashboard.php';
    }
}
