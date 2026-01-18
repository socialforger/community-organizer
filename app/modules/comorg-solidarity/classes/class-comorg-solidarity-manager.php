<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Solidarity – Manager
 *
 * Gestisce fondi, crediti, aiuti e movimenti solidali.
 */
class ComOrg_Solidarity_Manager {

    public static function init() {
        add_action( 'comorg_solidarity_add', array( __CLASS__, 'add_movement' ), 10, 4 );
    }

    /**
     * Aggiunge un movimento solidale
     */
    public static function add_movement( $user_id, $amount, $type, $description = '' ) {

        if ( ! $user_id || ! $amount || ! $type ) {
            return false;
        }

        // Salva nel DB
        ComOrg_Solidarity_DB::add( $user_id, $amount, $type, $description );

        // Trigger notifiche
        do_action( 'comorg_solidarity_movement_added', $user_id, $amount, $type, $description );

        return true;
    }

    /**
     * Calcola saldo solidarietà per utente
     */
    public static function get_balance( $user_id ) {

        $entries = ComOrg_Solidarity_DB::get_by_user( $user_id );
        $total   = 0;

        if ( $entries ) {
            foreach ( $entries as $entry ) {
                $total += floatval( $entry->amount );
            }
        }

        return $total;
    }
}
