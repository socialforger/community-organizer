<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Cashback Module
 */
class ComOrg_Module_Cashback {

    public static function init() {
        self::includes();
        self::hooks();
    }

    protected static function includes() {
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-cashback/classes/class-comorg-cashback-db.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-cashback/classes/class-comorg-cashback-manager.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-cashback/classes/class-comorg-cashback-hooks.php';
    }

    protected static function hooks() {
        ComOrg_Cashback_DB::init();
        ComOrg_Cashback_Manager::init();
        ComOrg_Cashback_Hooks::init();
    }
}

ComOrg_Module_Cashback::init();
