<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Notifications – Manager
 *
 * Gestisce la logica di invio notifiche (astratto, indipendente dal provider).
 */
class ComOrg_Notifications_Manager {

    public static function init() {
        add_action( 'comorg_notify', array( __CLASS__, 'send' ), 10, 3 );
    }

    /**
     * Invia una notifica tramite il provider attivo
     */
    public static function send( $user_id, $message, $args = array() ) {

        if ( ! $user_id || ! $message ) {
            return false;
        }

        // Provider OneSignal
        if ( class_exists( 'ComOrg_Notifications_OneSignal' ) ) {
            return ComOrg_Notifications_OneSignal::send( $user_id, $message, $args );
        }

        return false;
    }
}
