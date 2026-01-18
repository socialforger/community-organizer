<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Profile Logic
 */
class ComOrg_Profile {

    public static function init() {
        // quando il profilo viene aggiornato
        add_action( 'xprofile_updated_profile', array( __CLASS__, 'sync_profile_to_meta' ), 10, 5 );
    }

    /**
     * Sincronizza campi BuddyBoss → meta + member_type + ruolo
     */
    public static function sync_profile_to_meta( $user_id, $posted_field_ids, $errors, $old_values, $new_values ) {

        // 1. Dati utente individuale (esempi di nomi campi)
        $first_name = xprofile_get_field_data( 'Nome', $user_id );
        $last_name  = xprofile_get_field_data( 'Cognome', $user_id );

        if ( $first_name ) {
            update_user_meta( $user_id, 'first_name', $first_name );
        }
        if ( $last_name ) {
            update_user_meta( $user_id, 'last_name', $last_name );
        }

        // 2. Domanda: sei rappresentante legale?
        $is_legal_rep = xprofile_get_field_data( 'Sei il rappresentante legale di una organizzazione?', $user_id );

        // 3. Tipo di organizzazione (menu a tendina)
        $org_type = xprofile_get_field_data( 'Tipo di organizzazione', $user_id );

        // 4. Assegnazione member_type + ruolo

        if ( $is_legal_rep === 'Sì' ) {

            // utente collettivo
            if ( function_exists( 'bp_set_member_type' ) ) {
                bp_set_member_type( $user_id, 'utente_collettivo' );
            }

            if ( $org_type === 'Gruppo di acquisto' ) {
                update_user_meta( $user_id, '_comorg_role', 'organizzazione' );
                update_user_meta( $user_id, '_comorg_group_type', 'gas' );
            } elseif ( $org_type === 'Produttore' ) {
                update_user_meta( $user_id, '_comorg_role', 'produttore' );
            } elseif ( $org_type === 'Organizzazione / Ente gestore' ) {
                update_user_meta( $user_id, '_comorg_role', 'organizzazione' );
            }

        } else {

            // utente individuale
            if ( function_exists( 'bp_set_member_type' ) ) {
                bp_set_member_type( $user_id, 'utente_individuale' );
            }

            // ruolo base: cittadino (puoi raffinare con altri campi)
            if ( ! get_user_meta( $user_id, '_comorg_role', true ) ) {
                update_user_meta( $user_id, '_comorg_role', 'cittadino' );
            }
        }
    }
}
