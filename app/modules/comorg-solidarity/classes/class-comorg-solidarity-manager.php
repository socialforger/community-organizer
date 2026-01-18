<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Solidarietà – Manager
 *
 * Responsabilità:
 * - Calcolo quota solidarietà
 * - Creazione donazione Charitable
 * - Collegamento ordine ↔ donazione
 */
class ComOrg_Solidarity_Manager {

    /**
     * Calcola la quota totale per un item dell’ordine.
     */
    public static function calculate_quota( $product_id, $qty ) {

        $quota = ComOrg_Solidarity_DB::get_product_quota( $product_id );

        if ( $quota <= 0 ) {
            return 0;
        }

        return floatval( $quota ) * intval( $qty );
    }


    /**
     * Crea una donazione Charitable.
     */
    public static function create_donation( $order, $product_id, $qty ) {

        if ( ! class_exists( 'Charitable' ) ) {
            return false;
        }

        $campaign_id = ComOrg_Solidarity_DB::get_product_campaign( $product_id );

        if ( ! $campaign_id ) {
            return false;
        }

        $amount = self::calculate_quota( $product_id, $qty );

        if ( $amount <= 0 ) {
            return false;
        }

        $donation_id = charitable_get_donation( array(
            'campaign_id'    => $campaign_id,
            'amount'         => $amount,
            'gateway'        => 'woocommerce',
            'transaction_id' => $order->get_id(),
            'donor'          => array(
                'email'      => $order->get_billing_email(),
                'first_name' => $order->get_billing_first_name(),
                'last_name'  => $order->get_billing_last_name(),
            ),
        ) );

        if ( $donation_id ) {
            ComOrg_Solidarity_DB::save_order_donation( $order->get_id(), $donation_id );
        }

        return $donation_id;
    }
}
