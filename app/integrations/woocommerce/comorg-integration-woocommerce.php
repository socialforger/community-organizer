<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – WooCommerce Integration
 */
class ComOrg_Integration_WooCommerce {

    public static function init() {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        add_action( 'init', array( __CLASS__, 'register_hooks' ) );
    }

    public static function register_hooks() {
        // Esempio: aggiungi un campo ordine
        // add_action( 'woocommerce_checkout_update_order_meta', ... );
    }
}

ComOrg_Integration_WooCommerce::init();
