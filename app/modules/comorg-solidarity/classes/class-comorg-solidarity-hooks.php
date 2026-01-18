<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Solidarietà – Hooks
 *
 * Responsabilità:
 * - Aggiungere campi nel prodotto WooCommerce
 * - Salvare meta prodotto
 * - Intercettare ordine completato
 * - Chiamare il Manager per creare la donazione
 */
class ComOrg_Solidarity_Hooks {

    public static function init() {

        // Campi nel prodotto
        add_action( 'woocommerce_product_options_general_product_data', array( __CLASS__, 'add_product_fields' ) );
        add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'save_product_fields' ) );

        // Donazione al completamento ordine
        add_action( 'woocommerce_order_status_completed', array( __CLASS__, 'process_order' ) );
    }


    /**
     * Aggiunge i campi nel pannello prodotto WooCommerce.
     */
    public static function add_product_fields() {

        echo '<div class="options_group">';

        woocommerce_wp_text_input( array(
            'id'          => '_comorg_solidarity_amount',
            'label'       => __( 'Quota solidarietà (€)', 'comorg' ),
            'type'        => 'number',
            'desc_tip'    => true,
            'description' => __( 'Importo fisso da destinare alla campagna.', 'comorg' ),
            'custom_attributes' => array(
                'step' => '0.01',
                'min'  => '0',
            ),
        ) );

        $campaigns = self::get_charitable_campaigns();

        woocommerce_wp_select( array(
            'id'          => '_comorg_solidarity_campaign',
            'label'       => __( 'Campagna Charitable', 'comorg' ),
            'options'     => $campaigns,
            'desc_tip'    => true,
            'description' => __( 'Seleziona la campagna da finanziare.', 'comorg' ),
        ) );

        echo '</div>';
    }


    /**
     * Salva i meta del prodotto.
     */
    public static function save_product_fields( $post_id ) {

        if ( isset( $_POST['_comorg_solidarity_amount'] ) ) {
            update_post_meta(
                $post_id,
                '_comorg_solidarity_amount',
                wc_clean( $_POST['_comorg_solidarity_amount'] )
            );
        }

        if ( isset( $_POST['_comorg_solidarity_campaign'] ) ) {
            update_post_meta(
                $post_id,
                '_comorg_solidarity_campaign',
                wc_clean( $_POST['_comorg_solidarity_campaign'] )
            );
        }
    }


    /**
     * Recupera campagne Charitable.
     */
    protected static function get_charitable_campaigns() {

        if ( ! class_exists( 'Charitable' ) ) {
            return array( '' => __( 'Charitable non attivo', 'comorg' ) );
        }

        $posts = get_posts( array(
            'post_type'      => 'campaign',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ) );

        $options = array( '' => __( '— Nessuna —', 'comorg' ) );

        foreach ( $posts as $p ) {
            $options[ $p->ID ] = $p->post_title;
        }

        return $options;
    }


    /**
     * Processa l’ordine e crea la donazione.
     */
    public static function process_order( $order_id ) {

        $order = wc_get_order( $order_id );

        if ( ! $order ) {
            return;
        }

        foreach ( $order->get_items() as $item ) {

            $product_id = $item->get_product_id();
            $qty        = $item->get_quantity();

            ComOrg_Solidarity_Manager::create_donation( $order, $product_id, $qty );
        }
    }
}
