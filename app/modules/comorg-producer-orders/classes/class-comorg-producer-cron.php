<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Producer Orders – Cron
 */
class ComOrg_Producer_Cron {

    public static function init() {
        add_action( 'comorg_producer_orders_daily', array( __CLASS__, 'daily_check' ) );

        if ( ! wp_next_scheduled( 'comorg_producer_orders_daily' ) ) {
            wp_schedule_event( time(), 'daily', 'comorg_producer_orders_daily' );
        }
    }

    /**
     * Controllo giornaliero ordini produttore
     */
    public static function daily_check() {

        $orders = get_posts( array(
            'post_type'      => 'comorg_producer_order',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_closed',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        ) );

        foreach ( $orders as $order ) {

            $deadline = get_post_meta( $order->ID, '_deadline', true );

            if ( $deadline && strtotime( $deadline ) < time() ) {
                do_action( 'comorg_producer_order_close', $order->ID );
            }
        }
    }
}
