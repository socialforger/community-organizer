<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Enqueue admin CSS
 */
if ( ! function_exists( 'comorg_admin_enqueue_script' ) ) {
    function comorg_admin_enqueue_script() {
        wp_enqueue_style(
            'comorg-admin-css',
            plugin_dir_url( __FILE__ ) . 'style.css',
            array(),
            COMORG_VERSION
        );
    }
    add_action( 'admin_enqueue_scripts', 'comorg_admin_enqueue_script' );
}

/**
 * Settings sections
 */
if ( ! function_exists( 'comorg_get_settings_sections' ) ) {
    function comorg_get_settings_sections() {

        $settings = array(
            'comorg_settings_section' => array(
                'page'  => 'comorg',
                'title' => __( 'ComOrg Settings', 'comorg' ),
            ),
        );

        return (array) apply_filters( 'comorg_get_settings_sections', $settings );
    }
}

/**
 * Fields for a specific section
 */
if ( ! function_exists( 'comorg_get_settings_fields_for_section' ) ) {
    function comorg_get_settings_fields_for_section( $section_id = '' ) {

        if ( empty( $section_id ) ) {
            return false;
        }

        $fields = comorg_get_settings_fields();
        $retval = isset( $fields[ $section_id ] ) ? $fields[ $section_id ] : false;

        return (array) apply_filters( 'comorg_get_settings_fields_for_section', $retval, $section_id );
    }
}

/**
 * All settings fields
 */
if ( ! function_exists( 'comorg_get_settings_fields' ) ) {
    function comorg_get_settings_fields() {

        $fields = array();

        $fields['comorg_settings_section'] = array(

            'comorg_enable_feature' => array(
                'title'             => __( 'Enable ComOrg Feature', 'comorg' ),
                'callback'          => 'comorg_settings_callback_enable_feature',
                'sanitize_callback' => 'absint',
                'args'              => array(),
            ),

        );

        return (array) apply_filters( 'comorg_get_settings_fields', $fields );
    }
}

/**
 * Field callback
 */
if ( ! function_exists( 'comorg_settings_callback_enable_feature' ) ) {
    function comorg_settings_callback_enable_feature() {
        ?>
        <input name="comorg_enable_feature"
               id="comorg_enable_feature"
               type="checkbox"
               value="1"
               <?php checked( comorg_is_feature_enabled() ); ?> />

        <label for="comorg_enable_feature">
            <?php _e( 'Enable this ComOrg option', 'comorg' ); ?>
        </label>
        <?php
    }
}

/**
 * Getter for option
 */
if ( ! function_exists( 'comorg_is_feature_enabled' ) ) {
    function comorg_is_feature_enabled( $default = 1 ) {
        return (bool) apply_filters(
            'comorg_is_feature_enabled',
            (bool) get_option( 'comorg_enable_feature', $default )
        );
    }
}

/******************************************************
 * Add section in BuddyBoss Platform settings
 ******************************************************/

if ( ! function_exists( 'comorg_bp_admin_setting_general_register_fields' ) ) {
    function comorg_bp_admin_setting_general_register_fields( $setting ) {

        // Add section
        $setting->add_section(
            'comorg_addon',
            __( 'ComOrg Settings', 'comorg' )
        );

        // Add field
        $setting->add_field(
            'bp-enable-comorg',
            __( 'Enable ComOrg', 'comorg' ),
            'comorg_admin_general_setting_callback_enable',
            'intval',
            array()
        );
    }

    add_action(
        'bp_admin_setting_general_register_fields',
        'comorg_bp_admin_setting_general_register_fields'
    );
}

/**
 * Callback for BuddyBoss general settings
 */
if ( ! function_exists( 'comorg_admin_general_setting_callback_enable' ) ) {
    function comorg_admin_general_setting_callback_enable() {
        ?>
        <input id="bp-enable-comorg"
               name="bp-enable-comorg"
               type="checkbox"
               value="1"
               <?php checked( comorg_enable_in_bb_settings() ); ?> />

        <label for="bp-enable-comorg">
            <?php _e( 'Enable ComOrg integration', 'comorg' ); ?>
        </label>
        <?php
    }
}

/**
 * Getter for BuddyBoss general setting
 */
if ( ! function_exists( 'comorg_enable_in_bb_settings' ) ) {
    function comorg_enable_in_bb_settings( $default = false ) {
        return (bool) apply_filters(
            'comorg_enable_in_bb_settings',
            (bool) bp_get_option( 'bp-enable-comorg', $default )
        );
    }
}

/******************************************************
 * Register ComOrg integration with BuddyBoss
 ******************************************************/

function comorg_register_integration() {
    require_once dirname( __FILE__ ) . '/integration/comorg-integration.php';

    buddypress()->integrations['comorg'] = new ComOrg_BuddyBoss_Integration();
}
add_action( 'bp_setup_integrations', 'comorg_register_integration' );
