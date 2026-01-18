<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Price Lists – Hooks
 *
 * Collega upload listini e sincronizzazione.
 */
class ComOrg_Price_Hooks {

    public static function init() {

        // Upload listino (es. da admin)
        add_action( 'comorg_price_list_uploaded', array( __CLASS__, 'on_list_uploaded' ), 10, 2 );

        // Esempio: trigger manuale
        add_action( 'admin_post_comorg_upload_price_list', array( __CLASS__, 'handle_upload' ) );
    }

    /**
     * Quando un listino viene caricato
     */
    public static function on_list_uploaded( $list_id, $rows ) {

        // Log
        do_action( 'comorg_log', 'Listino caricato: ' . $list_id );

        // Sincronizza
        do_action( 'comorg_price_list_sync', $list_id, $rows );
    }

    /**
     * Gestione upload listino da admin
     */
    public static function handle_upload() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Non autorizzato.', 'comorg' ) );
        }

        if ( empty( $_FILES['comorg_price_list'] ) ) {
            wp_die( __( 'Nessun file caricato.', 'comorg' ) );
        }

        $file = $_FILES['comorg_price_list'];

        $path = $file['tmp_name'];

        // Parse CSV
        $rows = ComOrg_Price_Parser::parse_csv( $path );
        $rows = ComOrg_Price_Parser::validate( $rows );

        // ID listino (timestamp)
        $list_id = time();

        // Trigger evento
        do_action( 'comorg_price_list_uploaded', $list_id, $rows );

        wp_redirect( admin_url( 'admin.php?page=comorg-price-lists&uploaded=1' ) );
        exit;
    }
}
