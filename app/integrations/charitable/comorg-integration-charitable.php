<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Charitable Integration
 */
class ComOrg_Integration_Charitable {

    public static function init() {

        if ( ! class_exists( 'Charitable' ) ) {
            return;
        }

        add_action( 'init', array( __CLASS__, 'register_hooks' ) );
    }

    public static function register_hooks() {
        // Esempio: sincronizza donazioni
        // add_action( 'charitable_after_donation', ... );
    }
}

ComOrg_Integration_Charitable::init();
