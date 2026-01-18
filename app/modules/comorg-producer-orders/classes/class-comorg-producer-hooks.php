<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Producer Orders – Hooks
 */
class ComOrg_Producer_Hooks {

    public static function init() {

        // Quando un ordine produttore viene creato
        add_action( 'comorg_producer_order_created', array( __CLASS__, 'notify_created' ), 10, 2 );

        // Quando un ordine produttore viene chiuso
        add_action( 'comorg_producer_order_closed', array( __CLASS__, 'notify_closed' ), 10, 1 );
    }

    /**
     * Notifica ordine creato
     */
    public static function notify_created( $order_id, $producer_id ) {

        $message = sprintf(
            __( 'È stato creato un nuovo ordine produttore (#%d).', 'comorg' ),
            $order_id
        );

        do_action( 'comorg_notify', $producer_id, $message );
    }

    /**
     * Notifica ordine chiuso
     */
    public static function notify_closed( $order_id ) {

        $producer_id = get_post_meta( $order_id, '_producer_id', true );

        $message = sprintf(
            __( 'L’ordine produttore #%d è stato chiuso.', 'comorg' ),
            $order_id
        );

        do_action( 'comorg_notify', $producer_id, $message );
    }
}
