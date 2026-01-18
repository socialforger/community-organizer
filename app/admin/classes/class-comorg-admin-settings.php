<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Settings Page
 */
class ComOrg_Admin_Settings {

    public static function render() {
        ?>
        <div class="wrap comorg-admin-wrap">
            <h1><?php _e( 'ComOrg Settings', 'comorg' ); ?></h1>

            <form method="post" action="options.php">
                <?php
                settings_fields( 'comorg_settings' );
                do_settings_sections( 'comorg_settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
