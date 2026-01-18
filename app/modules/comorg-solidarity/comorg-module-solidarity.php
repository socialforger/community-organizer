<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Solidarity Module
 */
class ComOrg_Module_Solidarity {

    public static function init() {
        self::includes();
        self::hooks();
    }

    protected static function includes() {
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-solidarity/classes/class-comorg-solidarity-manager.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-solidarity/classes/class-comorg-solidarity-hooks.php';
    }

    protected static function hooks() {
        ComOrg_Solidarity_Manager::init();
        ComOrg_Solidarity_Hooks::init();
    }
}

ComOrg_Module_Solidarity::init();
