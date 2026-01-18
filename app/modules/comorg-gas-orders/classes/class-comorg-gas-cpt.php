<?php
defined( 'ABSPATH' ) || exit;

class ComOrg_GAS_CPT {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'register_cpt' ) );
    }

    public static function register_cpt() {
        // register_post_type( 'comorg_gas_order', ... );
    }
}
