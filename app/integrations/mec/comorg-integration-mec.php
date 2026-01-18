<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Modern Events Calendar Integration
 */
class ComOrg_Integration_MEC {

    public static function init() {

        if ( ! defined( 'MECEXEC' ) ) {
            return;
        }

        add_action( 'init', array( __CLASS__, 'register_hooks' ) );
    }

    public static function register_hooks() {
        // Esempio: sincronizza eventi MEC con gruppi ComOrg
        // add_action( 'mec_event_saved', ... );
    }
}

ComOrg_Integration_MEC::init();
