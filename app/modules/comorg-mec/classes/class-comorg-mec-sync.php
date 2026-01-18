<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg MEC – Sync Handler
 *
 * Sincronizza eventi MEC con gruppi, moduli o dashboard ComOrg.
 */
class ComOrg_MEC_Sync {

    protected static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function init() {
        add_action( 'mec_event_saved', array( __CLASS__, 'sync_event' ), 10, 2 );
    }

    /**
     * Sincronizza un evento MEC con ComOrg
     */
    public static function sync_event( $event_id, $event_data ) {

        if ( ! $event_id ) {
            return;
        }

        // Esempio: salva meta per collegare evento a ComOrg
        update_post_meta( $event_id, '_comorg_synced', 1 );

        do_action( 'comorg_mec_event_synced', $event_id, $event_data );
    }
}
