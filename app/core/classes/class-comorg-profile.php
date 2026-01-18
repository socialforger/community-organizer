<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Profile
 *
 * Gestisce:
 * - Assegnazione BuddyBoss Profile Type dopo onboarding
 * - Slug organizzazione / produttore / GAS
 * - Meta utente necessari ai moduli ComOrg
 * - Integrazione con campi condizionali BuddyBoss
 */
class ComOrg_Profile {

    public static function init() {

        // Hook dopo salvataggio profilo BuddyBoss
        add_action( 'xprofile_updated_profile', array( __CLASS__, 'process_onboarding' ), 20, 5 );

        // Tab prodotti nel profilo produttore
        add_action( 'bp_setup_nav', array( __CLASS__, 'add_products_tab' ), 100 );
    }


    /**
     * ------------------------------------------------------------
     * 1. ONBOARDING PF/PG
     * ------------------------------------------------------------
     *
     * Questo metodo viene chiamato ogni volta che l’utente salva
     * il profilo BuddyBoss. Noi intercettiamo SOLO il primo salvataggio
     * dopo il magic link, quando l’utente completa i campi condizionali.
     */
    public static function process_onboarding( $user_id ) {

        // Se l’utente ha già un Profile Type → non fare nulla
        if ( bp_get_member_type( $user_id ) ) {
            return;
        }

        /**
         * Campi condizionali BuddyBoss:
         *
         * - "Sei rappresentante legale?" → sì/no
         * - "Tipo di organizzazione" → produttore / GAS / organizzazione
         * - "Nome organizzazione"
         */

        $is_legal = xprofile_get_field_data( 'Sei rappresentante legale?', $user_id );
        $org_type = xprofile_get_field_data( 'Tipo di organizzazione', $user_id );
        $org_name = xprofile_get_field_data( 'Nome organizzazione', $user_id );

        /**
         * PF → Cittadino
         */
        if ( strtolower( $is_legal ) !== 'sì' ) {

            bp_set_member_type( $user_id, 'cittadino' );

            update_user_meta( $user_id, '_comorg_identity', 'pf' );
            update_user_meta( $user_id, '_comorg_profile_ready', 1 );

            return;
        }

        /**
         * PG → in base al tipo di organizzazione
         */
        update_user_meta( $user_id, '_comorg_identity', 'pg' );

        switch ( strtolower( $org_type ) ) {

            case 'produttore':
                bp_set_member_type( $user_id, 'produttore' );

                // Slug produttore
                $slug = 'prod_' . comorg_generate_slug( 10 );
                update_user_meta( $user_id, '_comorg_producer_slug', $slug );
                break;

            case 'gas':
            case 'gruppo di acquisto':
                bp_set_member_type( $user_id, 'gas' );

                // Slug GAS
                $slug = 'gas_' . comorg_generate_slug( 10 );
                update_user_meta( $user_id, '_comorg_gas_slug', $slug );
                break;

            case 'organizzazione':
            default:
                bp_set_member_type( $user_id, 'organizzazione' );

                // Slug organizzazione
                $slug = comorg_sanitize_slug( $org_name );
                update_user_meta( $user_id, '_comorg_org_slug', $slug );
                break;
        }

        update_user_meta( $user_id, '_comorg_profile_ready', 1 );
    }



    /**
     * ------------------------------------------------------------
     * 2. TAB "PRODOTTI" NEL PROFILO PRODUTTORE
     * ------------------------------------------------------------
     */
    public static function add_products_tab() {

        if ( ! function_exists( 'bp_core_new_nav_item' ) ) {
            return;
        }

        bp_core_new_nav_item( array(
            'name'                => __( 'Prodotti', 'comorg' ),
            'slug'                => 'prodotti',
            'screen_function'     => array( __CLASS__, 'render_products_tab' ),
            'default_subnav_slug' => 'prodotti',
            'position'            => 80,
            'show_for_displayed_user' => true,
        ) );
    }


    public static function render_products_tab() {

        add_action( 'bp_template_content', array( __CLASS__, 'products_tab_content' ) );
        bp_core_load_template( 'members/single/plugins' );
    }


    public static function products_tab_content() {

        $user_id = bp_displayed_user_id();
        $products = comorg_get_products_by_user( $user_id );

        echo '<div class="comorg-producer-products">';

        if ( $products->have_posts() ) {

            echo '<ul class="products">';

            while ( $products->have_posts() ) {
                $products->the_post();
                global $product;

                echo '<li class="product">';

                echo '<a href="' . get_permalink() . '">';
                echo woocommerce_get_product_thumbnail();
                echo '</a>';

                echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';

                if ( $product ) {
                    echo '<span class="price">' . $product->get_price_html() . '</span>';
                }

                if ( get_current_user_id() === $user_id ) {
                    echo '<p><a class="button" href="' . get_edit_post_link() . '">' . __( 'Modifica prodotto', 'comorg' ) . '</a></p>';
                }

                echo '</li>';
            }

            echo '</ul>';

        } else {
            echo '<p>' . __( 'Nessun prodotto disponibile.', 'comorg' ) . '</p>';
        }

        echo '</div>';

        wp_reset_postdata();
    }
}
