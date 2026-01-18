<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Onboarding Dinamico (Admin)
 */
class ComOrg_Admin_Onboarding {

    const OPTION = 'comorg_onboarding_map';

    public static function render_page() {

        if ( isset( $_POST['comorg_onboarding_nonce'] ) ) {
            self::save_settings();
        }

        $settings = get_option( self::OPTION, array() );

        $fields = self::get_buddyboss_fields();

        include __DIR__ . '/../views/admin-onboarding.php';
    }


    /**
     * Salva configurazione onboarding dinamico.
     */
    protected static function save_settings() {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        check_admin_referer( 'comorg_onboarding_action', 'comorg_onboarding_nonce' );

        $data = array(
            'field_pfpg'  => sanitize_text_field( $_POST['field_pfpg'] ?? '' ),
            'field_type'  => sanitize_text_field( $_POST['field_type'] ?? '' ),
            'field_name'  => sanitize_text_field( $_POST['field_name'] ?? '' ),
            'map_types'   => array(),
            'slug_rules'  => array(),
        );

        // Mappatura valori → Profile Types
        if ( ! empty( $_POST['map_key'] ) && ! empty( $_POST['map_value'] ) ) {

            foreach ( $_POST['map_key'] as $i => $key ) {

                $key   = strtolower( trim( sanitize_text_field( $key ) ) );
                $value = sanitize_text_field( $_POST['map_value'][ $i ] ?? '' );

                if ( $key && $value ) {
                    $data['map_types'][ $key ] = $value;
                }
            }
        }

        // Regole slug
        if ( ! empty( $_POST['slug_profile_type'] ) ) {

            foreach ( $_POST['slug_profile_type'] as $i => $ptype ) {

                $ptype = sanitize_text_field( $ptype );

                $data['slug_rules'][ $ptype ] = array(
                    'method' => sanitize_text_field( $_POST['slug_method'][ $i ] ?? 'random' ),
                    'prefix' => sanitize_text_field( $_POST['slug_prefix'][ $i ] ?? '' ),
                    'length' => intval( $_POST['slug_length'][ $i ] ?? 10 ),
                );
            }
        }

        update_option( self::OPTION, $data );
    }


    /**
     * Recupera tutti i campi BuddyBoss (dinamici).
     */
    protected static function get_buddyboss_fields() {

        if ( ! function_exists( 'bp_xprofile_get_groups' ) ) {
            return array();
        }

        $groups = bp_xprofile_get_groups( array(
            'fetch_fields' => true,
        ) );

        $fields = array();

        foreach ( $groups as $group ) {
            foreach ( $group->fields as $field ) {
                $fields[] = $field->name;
            }
        }

        return $fields;
    }
}
