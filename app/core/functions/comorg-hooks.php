<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Core Hooks
 */

function comorg_core_init() {
    // Auth (magic link)
    if ( class_exists( 'ComOrg_Auth' ) ) {
        ComOrg_Auth::init();
    }

    // Profile logic
    if ( class_exists( 'ComOrg_Profile' ) ) {
        ComOrg_Profile::init();
    }
}
add_action( 'plugins_loaded', 'comorg_core_init', 20 );

/**
 * Redirect login/registrazione standard a URL custom
 */
function comorg_override_login_register_urls() {

    // wp-login.php → /accedi
    if ( isset( $_SERVER['REQUEST_URI'] ) && false !== strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) ) {
        if ( ! isset( $_GET['action'] ) || $_GET['action'] === 'login' ) {
            wp_redirect( home_url( '/accedi/' ) );
            exit;
        }
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'register' ) {
            wp_redirect( home_url( '/registrati/' ) );
            exit;
        }
    }

    // BuddyBoss login/registration (se usano pagine dedicate)
    if ( function_exists( 'bp_get_signup_page' ) ) {
        add_filter( 'bp_get_signup_page', function( $url ) {
            return home_url( '/registrati/' );
        } );
    }

    if ( function_exists( 'bp_get_login_page' ) ) {
        add_filter( 'bp_get_login_page', function( $url ) {
            return home_url( '/accedi/' );
        } );
    }
}
add_action( 'init', 'comorg_override_login_register_urls', 1 );

/**
 * WooCommerce: aggiunta quota associativa al primo acquisto in un sotto-gruppo GAS
 * (struttura base, da completare con la tua logica di mapping prodotto/GAS)
 */
function comorg_maybe_add_association_fee( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {

    // Qui dovrai capire se il prodotto appartiene a un sotto-gruppo GAS
    // e identificare il gruppo territoriale padre.
    // Per ora lasciamo solo la struttura.

    // Esempio:
    // $group_id = comorg_get_group_from_product( $product_id );
    // if ( ! $group_id ) return;

    // $user_id = get_current_user_id();
    // if ( ! $user_id ) return;

    // if ( ! comorg_user_is_association_member( $user_id, $group_id ) ) {
    //     $fee_product_id = get_option( 'comorg_association_fee_product_id' );
    //     if ( $fee_product_id ) {
    //         WC()->cart->add_to_cart( $fee_product_id, 1 );
    //     }
    // }

}
add_action( 'woocommerce_add_to_cart', 'comorg_maybe_add_association_fee', 10, 6 );
