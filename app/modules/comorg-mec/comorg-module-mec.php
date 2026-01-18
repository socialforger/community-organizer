<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – MEC Module
 */
class ComOrg_Module_MEC {

    public static function init() {
        self::includes();
        self::hooks();
    }

    protected static function includes() {
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-mec/classes/class-comorg-mec-sync.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-mec/classes/class-comorg-mec-events.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-mec/classes/class-comorg-mec-hooks.php';
    }

    protected static function hooks() {
        ComOrg_MEC_Sync::init();
        ComOrg_MEC_Events::init();
        ComOrg_MEC_Hooks::init();
    }
}

ComOrg_Module_MEC::init();
