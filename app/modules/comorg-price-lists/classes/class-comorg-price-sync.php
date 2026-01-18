<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Price Lists – Sync
 *
 * Sincronizza i listini con WooCommerce o altri moduli ComOrg.
 */
class ComOrg_Price_Sync {

    public static function init() {
        add_action( 'comorg_price_list_uploaded', array( __CLASS__, 'sync' ), 10, 2 );
    }

    /**
     * Sincronizza listino con WooCommerce
     */
    public static function sync( $list_id, $rows ) {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        foreach ( $rows as $row ) {

            $product_id = wc_get_product_id_by_sku( $row['sku'] );

            if ( ! $product_id ) {
                continue;
            }

            $product = wc_get_product( $product_id );

            if ( ! $product ) {
                continue;
            }

            // Aggiorna prezzo
            $product->set_regular_price( $row['price'] );
            $product->save();
        }

        do_action( 'comorg_price_list_synced', $list_id, count( $rows ) );
    }
}
