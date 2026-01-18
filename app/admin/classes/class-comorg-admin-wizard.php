<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Setup Wizard
 */
class ComOrg_Admin_Wizard {

    public static function render() {
        include COMORG_PLUGIN_DIR . 'app/admin/screens/wizard.php';
    }
}
