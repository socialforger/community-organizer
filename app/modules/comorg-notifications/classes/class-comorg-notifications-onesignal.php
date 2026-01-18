<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Notifications – OneSignal Provider
 */
class ComOrg_Notifications_OneSignal {

    public static function init() {
        // Hook per test o setup
    }

    /**
     * Invia una notifica tramite OneSignal
     */
    public static function send( $user_id, $message, $args = array() ) {

        if ( ! function_exists( 'onesignal_send_notification' ) ) {
            return false;
        }

        $defaults = array(
            'title' => __( 'ComOrg Notification', 'comorg' ),
            'url'   => home_url(),
        );

        $args = wp_parse_args( $args, $defaults );

        $payload = array(
            'include_external_user_ids' => array( (string) $user_id ),
            'contents'                  => array( 'en' => $message ),
            'headings'                  => array( 'en' => $args['title'] ),
            'url'                       => $args['url'],
        );

        return onesignal_send_notification( $payload );
    }
}
