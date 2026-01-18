<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – BuddyBoss Integration
 */
class ComOrg_Integration_BuddyBoss {

    public static function init() {

        if ( ! function_exists( 'buddypress' ) ) {
            return;
        }

        add_action( 'bp_init', array( __CLASS__, 'register_hooks' ) );
    }

    public static function register_hooks() {
        // Esempio: aggiungi meta ai gruppi
        // add_action( 'groups_group_after_save', ... );
    }
}

ComOrg_Integration_BuddyBoss::init();
