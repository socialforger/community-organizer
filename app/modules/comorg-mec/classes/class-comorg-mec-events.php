<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg MEC – Event Manager
 *
 * Gestisce la lettura, trasformazione e mapping degli eventi MEC.
 */
class ComOrg_MEC_Events {

    public static function init() {
        // Hook generici
    }

    /**
     * Recupera un evento MEC formattato per ComOrg
     */
    public static function get_event( $event_id ) {

        if ( ! function_exists( 'MEC' ) ) {
            return null;
        }

        $mec = MEC::getInstance( 'app.libraries.main' );

        $event = $mec->get_event( $event_id );

        if ( ! $event ) {
            return null;
        }

        return array(
            'id'          => $event_id,
            'title'       => $event->data->title ?? '',
            'start'       => $event->data->start ?? '',
            'end'         => $event->data->end ?? '',
            'location'    => $event->data->location ?? '',
            'organizer'   => $event->data->organizer ?? '',
            'description' => $event->data->content ?? '',
        );
    }

    /**
     * Recupera tutti gli eventi MEC
     */
    public static function get_all_events() {

        if ( ! function_exists( 'MEC' ) ) {
            return array();
        }

        $mec = MEC::getInstance( 'app.libraries.main' );

        $events = $mec->get_all_events();

        return $events ?: array();
    }
}
