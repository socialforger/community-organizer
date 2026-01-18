<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Notifications Module
 */
class ComOrg_Module_Notifications {

    public static function init() {
        self::includes();
        self::hooks();
    }

    protected static function includes() {
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-notifications/classes/class-comorg-notifications-manager.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-notifications/classes/class-comorg-notifications-onesignal.php';
        require_once COMORG_PLUGIN_DIR . 'app/modules/comorg-notifications/classes/class-comorg-notifications-hooks.php';
    }

    protected static function hooks() {
        ComOrg_Notifications_Manager::init();
        ComOrg_Notifications_OneSignal::init();
        ComOrg_Notifications_Hooks::init();
    }
}

ComOrg_Module_Notifications::init();
