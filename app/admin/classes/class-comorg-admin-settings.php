<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Admin Settings
 *
 * Gestisce:
 * - Registrazione opzioni ComOrg
 * - Caricamento impostazioni
 * - Punto centrale per future configurazioni admin
 */
class ComOrg_Admin_Settings {

    /**
     * Inizializza la classe.
     */
    public static function init() {

        // Registra le opzioni ComOrg
        add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
    }


    /**
     * Registra tutte le opzioni ComOrg.
     *
     * Ogni modulo può aggiungere le proprie opzioni qui.
     */
    public static function register_settings() {

        /**
         * Onboarding dinamico
         *
         * Opzione: comorg_onboarding_map
         * Contiene:
         * - campo PF/PG
         * - campo tipo organizzazione
         * - campo nome organizzazione
         * - mappatura valori → Profile Types
         * - regole slug
         */
        register_setting(
            'comorg_settings_group',
            'comorg_onboarding_map',
            array(
                'type'              => 'array',
                'sanitize_callback' => array( __CLASS__, 'sanitize_onboarding_map' ),
                'default'           => array(),
            )
        );
    }


    /**
     * Sanitizzazione dell’opzione comorg_onboarding_map.
     *
     * @param array $input
     * @return array
     */
    public static function sanitize_onboarding_map( $input ) {

        if ( ! is_array( $input ) ) {
            return array();
        }

        $output = array(
            'field_pfpg'  => sanitize_text_field( $input['field_pfpg'] ?? '' ),
            'field_type'  => sanitize_text_field( $input['field_type'] ?? '' ),
            'field_name'  => sanitize_text_field( $input['field_name'] ?? '' ),
            'map_types'   => array(),
            'slug_rules'  => array(),
        );

        // Mappatura valori → Profile Types
        if ( isset( $input['map_types'] ) && is_array( $input['map_types'] ) ) {

            foreach ( $input['map_types'] as $key => $value ) {

                $key   = strtolower( trim( sanitize_text_field( $key ) ) );
                $value = sanitize_text_field( $value );

                if ( $key && $value ) {
                    $output['map_types'][ $key ] = $value;
                }
            }
        }

        // Regole slug
        if ( isset( $input['slug_rules'] ) && is_array( $input['slug_rules'] ) ) {

            foreach ( $input['slug_rules'] as $ptype => $rule ) {

                $ptype = sanitize_text_field( $ptype );

                $output['slug_rules'][ $ptype ] = array(
                    'method' => sanitize_text_field( $rule['method'] ?? 'random' ),
                    'prefix' => sanitize_text_field( $rule['prefix'] ?? '' ),
                    'length' => intval( $rule['length'] ?? 10 ),
                );
            }
        }

        return $output;
    }


    /**
     * Recupera l’opzione onboarding dinamico.
     *
     * @return array
     */
    public static function get_onboarding_settings() {

        $defaults = array(
            'field_pfpg'  => '',
            'field_type'  => '',
            'field_name'  => '',
            'map_types'   => array(),
            'slug_rules'  => array(),
        );

        $settings = get_option( 'comorg_onboarding_map', array() );

        if ( ! is_array( $settings ) ) {
            $settings = array();
        }

        return wp_parse_args( $settings, $defaults );
    }
}
