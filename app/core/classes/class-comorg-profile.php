<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Profile
 *
 * Gestisce:
 * - Assegnazione dinamica BuddyBoss Profile Type (PF/PG)
 * - Lettura campi configurati dall’admin (onboarding dinamico)
 * - Slug produttore / GAS / organizzazione
 * - Meta utente necessari ai moduli ComOrg
 * - Tab "Prodotti" nel profilo produttore
 */
class ComOrg_Profile {

    const OPTION_ONBOARDING = 'comorg_onboarding_map';

    public static function init() {

        // Onboarding dinamico dopo salvataggio profilo BuddyBoss
        add_action( 'xprofile_updated_profile', array( __CLASS__, 'process_onboarding' ), 20, 5 );

        // Tab prodotti nel profilo produttore
        add_action( 'bp_setup_nav', array( __CLASS__, 'add_products_tab' ), 100 );
    }


    /**
     * Recupera configurazione onboarding dinamico.
     *
     * @return array
     */
    protected static function get_onboarding_settings() {

        $defaults = array(
            'field_pfpg'  => '',
            'field_type'  => '',
            'field_name'  => '',
            'map_types'   => array(),
            'slug_rules'  => array(),
        );

        $settings = get_option( self::OPTION_ONBOARDING, array() );

        if ( ! is_array( $settings ) ) {
            $settings = array();
        }

        return wp_parse_args( $settings, $defaults );
    }


    /**
     * Onboarding dinamico PF/PG + Profile Type.
     *
     * @param int $user_id
     */
    public static function process_onboarding( $user_id ) {

        // Se l’utente ha già un Profile Type → non fare nulla
        if ( function_exists( 'bp_get_member_type' ) && bp_get_member_type( $user_id ) ) {
            return;
        }

        $settings = self::get_onboarding_settings();

        $field_pfpg = $settings['field_pfpg'];
        $field_type = $settings['field_type'];
        $field_name = $settings['field_name'];
        $map_types  = $settings['map_types'];
        $slug_rules = $settings['slug_rules'];

        // Se la configurazione non è completa, non facciamo nulla
        if ( empty( $field_pfpg ) || empty( $field_type ) ) {
            return;
        }

        // Lettura campi dinamici da BuddyBoss
        $is_legal = xprofile_get_field_data( $field_pfpg, $user_id );
        $org_type = xprofile_get_field_data( $field_type, $user_id );
        $org_name = $field_name ? xprofile_get_field_data( $field_name, $user_id ) : '';

        $is_legal_norm = strtolower( trim( (string) $is_legal ) );
        $org_type_norm = strtolower( trim( (string) $org_type ) );

        /**
         * PF → Cittadino (o altro Profile Type definito dall’admin in futuro)
         */
        if ( $is_legal_norm !== 'sì' && $is_legal_norm !== 'si' ) {

            if ( function_exists( 'bp_set_member_type' ) ) {
                bp_set_member_type( $user_id, 'cittadino' );
            }

            update_user_meta( $user_id, '_comorg_identity', 'pf' );
            update_user_meta( $user_id, '_comorg_profile_ready', 1 );

            return;
        }

        /**
         * PG → in base al tipo di organizzazione (mappato dall’admin)
         */
        update_user_meta( $user_id, '_comorg_identity', 'pg' );

        $profile_type = 'organizzazione';

        if ( ! empty( $map_types ) && isset( $map_types[ $org_type_norm ] ) ) {
            $profile_type = $map_types[ $org_type_norm ];
        }

        if ( function_exists( 'bp_set_member_type' ) ) {
            bp_set_member_type( $user_id, $profile_type );
        }

        // Slug dinamici in base alle regole configurate
        self::apply_slug_rules( $user_id, $profile_type, $org_name, $slug_rules );

        update_user_meta( $user_id, '_comorg_profile_ready', 1 );
    }


    /**
     * Applica le regole di generazione slug in base al Profile Type.
     *
     * @param int    $user_id
     * @param string $profile_type
     * @param string $org_name
     * @param array  $slug_rules
     */
    protected static function apply_slug_rules( $user_id, $profile_type, $org_name, $slug_rules ) {

        $rules = isset( $slug_rules[ $profile_type ] ) ? $slug_rules[ $profile_type ] : array();

        $method = isset( $rules['method'] ) ? $rules['method'] : 'random';
        $prefix = isset( $rules['prefix'] ) ? $rules['prefix'] : '';
        $length = isset( $rules['length'] ) ? (int) $rules['length'] : 10;

        $slug = '';

        switch ( $method ) {

            case 'sanitize_name':
                $slug = comorg_sanitize_slug( $org_name );
                break;

            case 'random':
            default:
                $slug = comorg_generate_slug( $length );
                break;
        }

        if ( $prefix ) {
            $slug = $prefix . $slug;
        }

        switch ( $profile_type ) {

            case 'produttore':
                update_user_meta( $user_id, '_comorg_producer_slug', $slug );
                break;

            case 'gas':
                update_user_meta( $user_id, '_comorg_gas_slug', $slug );
                break;

            case 'organizzazione':
            default:
                update_user_meta( $user_id, '_comorg_org_slug', $slug );
                break;
        }
    }


    /**
     * Tab "Prodotti" nel profilo produttore.
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

        $user_id  = bp_displayed_user_id();
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
