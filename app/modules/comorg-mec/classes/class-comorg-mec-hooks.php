<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg MEC – Hooks
 *
 * Collega MEC a ComOrg tramite hook.
 */
class ComOrg_MEC_Hooks {

    public static function init() {

        // Quando un evento MEC viene salvato → sincronizza
        add_action( 'mec_event_saved', array( __CLASS__, 'on_event_saved' ), 10, 2 );

        // Quando un evento MEC viene cancellato → aggiorna ComOrg
        add_action( 'before_delete_post', array( __CLASS__, 'on_event_deleted' ) );
    }

    /**
     * Evento MEC salvato
     */
    public static function on_event_saved( $event_id, $event_data ) {
        do_action( 'comorg_mec_event_saved', $event_id, $event_data );
    }

    /**
     * Evento MEC cancellato
     */
    public static function on_event_deleted( $post_id ) {

        if ( get_post_type( $post_id ) !== 'mec-events' ) {
            return;
        }

        do_action( 'comorg_mec_event_deleted', $post_id );
    }
}
