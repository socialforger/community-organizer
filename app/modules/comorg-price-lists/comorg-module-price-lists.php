<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Price Lists Module
 */
class ComOrg_Module_Price_Lists {

    public static function init() {
        self::includes();
        self::hooks();
    }

    protected static function includes() {
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-price-lists/classes/class-comorg-price-parser.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-price-lists/classes/class-comorg-price-sync.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-price-lists/classes/class-comorg-price-hooks.php';
    }

    protected static function hooks() {
        ComOrg_Price_Parser::init();
        ComOrg_Price_Sync::init();
        ComOrg_Price_Hooks::init();
    }
}

ComOrg_Module_Price_Lists::init();
