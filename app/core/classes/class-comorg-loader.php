<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Loader
 *
 * Carica core, moduli e integrazioni.
 */
class ComOrg_Loader {

    protected static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->includes();
        $this->init_modules();
    }

    private function includes() {

        // Core
        require_once COMORG_PLUGIN_DIR . 'app/core/classes/class-comorg-component.php';
        require_once COMORG_PLUGIN_DIR . 'app/core/classes/class-comorg-install.php';
        require_once COMORG_PLUGIN_DIR . 'app/core/classes/class-comorg-permissions.php';
        require_once COMORG_PLUGIN_DIR . 'app/core/classes/class-comorg-rest.php';
        require_once COMORG_PLUGIN_DIR . 'app/core/classes/class-comorg-groups.php';

        // Helpers
        require_once COMORG_PLUGIN_DIR . 'app/core/functions/comorg-helpers.php';
        require_once COMORG_PLUGIN_DIR . 'app/core/functions/comorg-hooks.php';

        // Admin
        require_once COMORG_PLUGIN_DIR . 'app/admin/classes/class-comorg-admin.php';
    }

    private function init_modules() {

        $modules = array(
            'comorg-gas-orders',
            'comorg-producer-orders',
            'comorg-price-lists',
            'comorg-cashback',
            'comorg-solidarity',
            'comorg-mec',
            'comorg-notifications',
        );

        foreach ( $modules as $module ) {
            $file = COMORG_PLUGIN_DIR . "app/modules/{$module}/{$module}.php";

            if ( file_exists( $file ) ) {
                require_once $file;
            }
        }
    }
}
