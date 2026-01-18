<?php
/**
 * Plugin Name: Community Organizer 
 * Plugin URI:  https://github.com/socialforger/community-organizer
 * Description: Buddyboss Framework for communities.
 * Author:      Socialforger
 * Version:     1.0.0
 * Text Domain: comorg
 * Domain Path: /languages/
 * License:     GPLv3 or later
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'ComOrg_Plugin' ) ) {

    /**
     * Main ComOrg Plugin Class
     */
    final class ComOrg_Plugin {

        /**
         * @var ComOrg_Plugin
         */
        protected static $_instance = null;

        /**
         * Singleton instance
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Block cloning
         */
        public function __clone() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin’ huh?', 'comorg' ), '1.0.0' );
        }

        /**
         * Block unserializing
         */
        public function __wakeup() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin’ huh?', 'comorg' ), '1.0.0' );
        }

        /**
         * Constructor
         */
        public function __construct() {
            $this->define_constants();
            $this->includes();
            $this->load_textdomain();
        }

        /**
         * Define plugin constants
         */
        private function define_constants() {
            $this->define( 'COMORG_PLUGIN_FILE', __FILE__ );
            $this->define( 'COMORG_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
            $this->define( 'COMORG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            $this->define( 'COMORG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
            $this->define( 'COMORG_VERSION', '1.0.0' );
        }

        /**
         * Define constant if not set
         */
        private function define( $name, $value ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }

        /**
         * Include core files
         */
        public function includes() {
            include_once COMORG_PLUGIN_DIR . 'functions.php';
            include_once COMORG_PLUGIN_DIR . 'integration/comorg-integration.php';
        }

        /**
         * Load translations
         */
        public function load_textdomain() {
            $locale = is_admin() && function_exists( 'get_user_locale' )
                ? get_user_locale()
                : get_locale();

            $locale = apply_filters( 'plugin_locale', $locale, 'comorg' );

            unload_textdomain( 'comorg' );

            load_textdomain(
                'comorg',
                WP_LANG_DIR . '/community-organizer/community-organizer-' . $locale . '.mo'
            );

            load_plugin_textdomain(
                'comorg',
                false,
                dirname( COMORG_PLUGIN_BASENAME ) . '/languages'
            );
        }
    }

    /**
     * Helper function to access the singleton
     */
    function ComOrg() {
        return ComOrg_Plugin::instance();
    }

    /**
     * Admin notice: BuddyBoss Platform missing
     */
    function comorg_notice_install_bb_platform() {
        echo '<div class="error"><p>';
        _e(
            '<strong>Community Organizer</strong> requires the BuddyBoss Platform plugin. Please install BuddyBoss Platform first.',
            'comorg'
        );
        echo '</p></div>';
    }

    /**
     * Admin notice: BuddyBoss Platform too old
     */
    function comorg_notice_update_bb_platform() {
        echo '<div class="error"><p>';
        _e(
            '<strong>Community Organizer</strong> requires BuddyBoss Platform version 1.2.6 or higher. Please update BuddyBoss Platform.',
            'comorg'
        );
        echo '</p></div>';
    }

    /**
     * Check if BuddyBoss Platform is active and compatible
     */
    function comorg_is_bb_platform_active() {
        return defined( 'BP_PLATFORM_VERSION' ) && version_compare( BP_PLATFORM_VERSION, '1.2.6', '>=' );
    }

    /**
     * Initialize plugin
     */
    function comorg_init() {

        if ( ! defined( 'BP_PLATFORM_VERSION' ) ) {
            add_action( 'admin_notices', 'comorg_notice_install_bb_platform' );
            add_action( 'network_admin_notices', 'comorg_notice_install_bb_platform' );
            return;
        }

        if ( version_compare( BP_PLATFORM_VERSION, '1.2.6', '<' ) ) {
            add_action( 'admin_notices', 'comorg_notice_update_bb_platform' );
            add_action( 'network_admin_notices', 'comorg_notice_update_bb_platform' );
            return;
        }

        // Load plugin
        ComOrg();
    }

    add_action( 'plugins_loaded', 'comorg_init', 9 );
}
