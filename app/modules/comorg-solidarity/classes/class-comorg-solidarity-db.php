<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Solidarietà – DB Layer
 *
 * Responsabilità:
 * - Lettura/scrittura meta prodotto (quota + campagna)
 * - Lettura/scrittura meta ordine (donazione collegata)
 */
class ComOrg_Solidarity_DB {

    /**
     * Quota solidarietà del prodotto.
     */
    public static function get_product_quota( $product_id ) {
        return floatval( get_post_meta( $product_id, '_comorg_solidarity_amount', true ) );
    }

    /**
     * Campagna Charitable associata al prodotto.
     */
    public static function get_product_campaign( $product_id ) {
        return intval( get_post_meta( $product_id, '_comorg_solidarity_campaign', true ) );
    }

    /**
     * Salva la donazione Charitable collegata all’ordine.
     */
    public static function save_order_donation( $order_id, $donation_id ) {
        update_post_meta( $order_id, '_comorg_solidarity_donation_id', intval( $donation_id ) );
    }

    /**
     * Recupera la donazione collegata all’ordine.
     */
    public static function get_order_donation( $order_id ) {
        return intval( get_post_meta( $order_id, '_comorg_solidarity_donation_id', true ) );
    }
}
