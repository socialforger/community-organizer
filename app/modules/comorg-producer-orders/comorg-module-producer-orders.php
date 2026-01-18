<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Producer Orders Module
 */
class ComOrg_Module_Producer_Orders {

    public static function init() {
        self::includes();
        self::hooks();
    }

    protected static function includes() {
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-producer-orders/classes/class-comorg-producer-cpt.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-producer-orders/classes/class-comorg-producer-manager.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-producer-orders/classes/class-comorg-producer-cron.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-producer-orders/classes/class-comorg-producer-hooks.php';
    }

    protected static function hooks() {
        ComOrg_Producer_CPT::init();
        ComOrg_Producer_Manager::init();
        ComOrg_Producer_Cron::init();
        ComOrg_Producer_Hooks::init();
    }
}

ComOrg_Module_Producer_Orders::init();
