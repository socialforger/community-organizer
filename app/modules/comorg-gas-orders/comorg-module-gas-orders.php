<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – GAS Orders Module
 */
class ComOrg_Module_GAS_Orders {

    public static function init() {
        self::includes();
        self::hooks();
    }

    protected static function includes() {
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-gas-orders/classes/class-comorg-gas-cpt.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-gas-orders/classes/class-comorg-gas-manager.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-gas-orders/classes/class-comorg-gas-cron.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-gas-orders/classes/class-comorg-gas-hooks.php';
    }

    protected static function hooks() {
        ComOrg_GAS_CPT::init();
        ComOrg_GAS_Manager::init();
        ComOrg_GAS_Cron::init();
        ComOrg_GAS_Hooks::init();
    }
}

ComOrg_Module_GAS_Orders::init();
