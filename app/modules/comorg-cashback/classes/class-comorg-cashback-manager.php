<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Cashback – Business Logic
 */
class ComOrg_Cashback_Manager {

    public static function init() {
        add_action( 'comorg_cashback_add', array( __CLASS__, 'add_cashback' ), 10, 3 );
    }

    /**
     * Add cashback to a user
     */
    public static function add_cashback( $user_id, $amount, $description = '' ) {

        if ( ! $user_id || ! $amount ) {
            return false;
        }

        // Save to DB
        ComOrg_Cashback_DB::add( $user_id, $amount, $description );

        // Trigger notification
        do_action( 'comorg_cashback_added', $user_id, $amount, $description );

        return true;
    }

    /**
     * Get total cashback for a user
     */
    public static function get_total( $user_id ) {
        $entries = ComOrg_Cashback_DB::get_by_user( $user_id );

        $total = 0;

        if ( $entries ) {
            foreach ( $entries as $entry ) {
                $total += floatval( $entry->amount );
            }
        }

        return $total;
    }
}
