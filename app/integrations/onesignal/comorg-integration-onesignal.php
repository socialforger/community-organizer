<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – OneSignal Integration
 */
class ComOrg_Integration_OneSignal {

    public static function init() {

        if ( ! function_exists( 'onesignal_send_notification' ) ) {
            return;
        }

        add_action( 'init', array( __CLASS__, 'register_hooks' ) );
    }

    public static function register_hooks() {
        // Esempio: invia notifica quando un ordine GAS si chiude
        // add_action( 'comorg_gas_order_closed', ... );
    }
}

ComOrg_Integration_OneSignal::init();
