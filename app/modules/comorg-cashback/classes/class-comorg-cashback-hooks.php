<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Cashback – Hooks
 */
class ComOrg_Cashback_Hooks {

    public static function init() {

        // Esempio: aggiungi cashback quando un ordine WooCommerce è completato
        add_action( 'woocommerce_order_status_completed', array( __CLASS__, 'on_wc_order_completed' ) );

        // Esempio: aggiungi cashback quando un ordine GAS si chiude
        add_action( 'comorg_gas_order_closed', array( __CLASS__, 'on_gas_order_closed' ), 10, 2 );
    }

    /**
     * WooCommerce: order completed
     */
    public static function on_wc_order_completed( $order_id ) {

        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            return;
        }

        $user_id = $order->get_user_id();

        // Esempio: 1% cashback
        $amount = $order->get_total() * 0.01;

        do_action( 'comorg_cashback_add', $user_id, $amount, 'WooCommerce order cashback' );
    }

    /**
     * GAS: order closed
     */
    public static function on_gas_order_closed( $order_id, $user_id ) {

        // Esempio: cashback fisso
        $amount = 2.00;

        do_action( 'comorg_cashback_add', $user_id, $amount, 'GAS order cashback' );
    }
}
