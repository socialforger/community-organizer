<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Producer Orders – Manager
 *
 * Gestisce la logica degli ordini produttore.
 */
class ComOrg_Producer_Manager {

    public static function init() {
        add_action( 'comorg_producer_order_create', array( __CLASS__, 'create_order' ), 10, 2 );
        add_action( 'comorg_producer_order_close', array( __CLASS__, 'close_order' ), 10, 1 );
    }

    /**
     * Crea un ordine produttore
     */
    public static function create_order( $producer_id, $args = array() ) {

        $defaults = array(
            'title'       => __( 'Nuovo Ordine Produttore', 'comorg' ),
            'description' => '',
        );

        $args = wp_parse_args( $args, $defaults );

        $order_id = wp_insert_post( array(
            'post_type'   => 'comorg_producer_order',
            'post_title'  => $args['title'],
            'post_content'=> $args['description'],
            'post_status' => 'publish',
        ) );

        if ( $order_id ) {
            update_post_meta( $order_id, '_producer_id', $producer_id );
            do_action( 'comorg_producer_order_created', $order_id, $producer_id );
        }

        return $order_id;
    }

    /**
     * Chiude un ordine produttore
     */
    public static function close_order( $order_id ) {

        if ( ! $order_id ) {
            return false;
        }

        update_post_meta( $order_id, '_closed', 1 );

        do_action( 'comorg_producer_order_closed', $order_id );

        return true;
    }
}
