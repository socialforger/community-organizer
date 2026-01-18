<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Producer Orders – CPT
 */
class ComOrg_Producer_CPT {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'register_cpt' ) );
    }

    /**
     * Registra il CPT "Ordini Produttore"
     */
    public static function register_cpt() {

        $labels = array(
            'name'               => __( 'Ordini Produttore', 'comorg' ),
            'singular_name'      => __( 'Ordine Produttore', 'comorg' ),
            'add_new'            => __( 'Nuovo Ordine', 'comorg' ),
            'add_new_item'       => __( 'Aggiungi Ordine Produttore', 'comorg' ),
            'edit_item'          => __( 'Modifica Ordine Produttore', 'comorg' ),
            'new_item'           => __( 'Nuovo Ordine Produttore', 'comorg' ),
            'view_item'          => __( 'Vedi Ordine Produttore', 'comorg' ),
            'search_items'       => __( 'Cerca Ordini Produttore', 'comorg' ),
            'not_found'          => __( 'Nessun ordine trovato', 'comorg' ),
            'not_found_in_trash' => __( 'Nessun ordine nel cestino', 'comorg' ),
        );

        $args = array(
            'label'               => __( 'Ordini Produttore', 'comorg' ),
            'labels'              => $labels,
            'public'              => false,
            'show_ui'             => true,
            'menu_icon'           => 'dashicons-store',
            'supports'            => array( 'title', 'editor', 'custom-fields' ),
            'capability_type'     => 'post',
            'rewrite'             => false,
        );

        register_post_type( 'comorg_producer_order', $args );
    }
}
