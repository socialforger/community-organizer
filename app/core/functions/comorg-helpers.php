<?php
defined( 'ABSPATH' ) || exit;

/**
 * ============================================================
 *  ComOrg – Helpers
 *  Funzioni generiche, centralizzate e riutilizzabili
 * ============================================================
 */


/**
 * ------------------------------------------------------------
 * 1. SLUGS
 * ------------------------------------------------------------
 */

/**
 * Genera uno slug alfanumerico casuale, senza caratteri speciali.
 *
 * @param int $length Lunghezza dello slug (default 12)
 * @return string
 */
function comorg_generate_slug( $length = 12 ) {

    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $slug  = '';

    for ( $i = 0; $i < $length; $i++ ) {
        $slug .= $chars[ wp_rand( 0, strlen( $chars ) - 1 ) ];
    }

    return $slug;
}


/**
 * Sanitizza una stringa rendendola alfanumerica.
 *
 * @param string $string
 * @return string
 */
function comorg_sanitize_slug( $string ) {

    $string = strtolower( $string );
    $string = preg_replace( '/[^a-z0-9]/', '', $string );

    if ( empty( $string ) ) {
        return comorg_generate_slug();
    }

    return $string;
}


/**
 * Genera uno username alfanumerico random.
 *
 * @return string
 */
function comorg_generate_username() {
    return 'u' . comorg_generate_slug( 10 );
}



/**
 * ------------------------------------------------------------
 * 2. UTENTE
 * ------------------------------------------------------------
 */

/**
 * Restituisce il Profile Type BuddyBoss dell’utente.
 *
 * @param int $user_id
 * @return string|null
 */
function comorg_get_profile_type( $user_id ) {

    if ( function_exists( 'bp_get_member_type' ) ) {
        return bp_get_member_type( $user_id );
    }

    return null;
}


/**
 * Verifica se l’utente ha un determinato Profile Type.
 *
 * @param int $user_id
 * @param string $type
 * @return bool
 */
function comorg_user_is( $user_id, $type ) {

    $current = comorg_get_profile_type( $user_id );
    return $current === $type;
}



/**
 * ------------------------------------------------------------
 * 3. GRUPPI
 * ------------------------------------------------------------
 */

/**
 * Restituisce il gruppo BuddyBoss da slug.
 *
 * @param string $slug
 * @return BP_Groups_Group|null
 */
function comorg_get_group_by_slug( $slug ) {

    if ( ! function_exists( 'groups_get_group' ) ) {
        return null;
    }

    $group = groups_get_group( array( 'slug' => $slug ) );

    if ( ! empty( $group->id ) ) {
        return $group;
    }

    return null;
}


/**
 * Crea uno slug random per un gruppo BuddyBoss.
 *
 * @return string
 */
function comorg_generate_group_slug() {
    return comorg_generate_slug( 12 );
}



/**
 * ------------------------------------------------------------
 * 4. PRODOTTI
 * ------------------------------------------------------------
 */

/**
 * Restituisce i prodotti WooCommerce creati da un utente.
 *
 * @param int $user_id
 * @return WP_Query
 */
function comorg_get_products_by_user( $user_id ) {

    $args = array(
        'post_type'      => 'product',
        'post_status'    => array( 'publish', 'pending', 'draft' ),
        'author'         => $user_id,
        'posts_per_page' => -1,
    );

    return new WP_Query( $args );
}



/**
 * ------------------------------------------------------------
 * 5. VALIDAZIONE
 * ------------------------------------------------------------
 */

/**
 * Verifica se una stringa è un indirizzo email valido.
 *
 * @param string $email
 * @return bool
 */
function comorg_is_valid_email( $email ) {
    return is_email( $email ) !== false;
}



/**
 * ------------------------------------------------------------
 * 6. DEBUG / LOG
 * ------------------------------------------------------------
 */

/**
 * Log ComOrg (solo se WP_DEBUG attivo).
 *
 * @param mixed $data
 * @param string $label
 */
function comorg_log( $data, $label = 'ComOrg' ) {

    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

        if ( is_array( $data ) || is_object( $data ) ) {
            $data = print_r( $data, true );
        }

        error_log( '[' . $label . '] ' . $data );
    }
}



/**
 * ------------------------------------------------------------
 * 7. SICUREZZA
 * ------------------------------------------------------------
 */

/**
 * Genera un token sicuro per magic link.
 *
 * @return string
 */
function comorg_generate_token() {
    return wp_hash( comorg_generate_slug( 20 ) . time() );
}


/**
 * Verifica se un token è scaduto.
 *
 * @param int $timestamp
 * @param int $ttl
 * @return bool
 */
function comorg_token_expired( $timestamp, $ttl = HOUR_IN_SECONDS ) {
    return ( time() - $timestamp ) > $ttl;
}



/**
 * ------------------------------------------------------------
 * 8. URL / REDIRECT
 * ------------------------------------------------------------
 */

/**
 * Restituisce l’URL di completamento profilo BuddyBoss.
 *
 * @param int $user_id
 * @return string
 */
function comorg_get_profile_edit_url( $user_id ) {

    if ( function_exists( 'bp_core_get_user_domain' ) ) {
        return bp_core_get_user_domain( $user_id ) . 'profile/edit/';
    }

    return admin_url( 'profile.php' );
}


/**
 * Redirect sicuro.
 *
 * @param string $url
 */
function comorg_safe_redirect( $url ) {
    wp_safe_redirect( esc_url_raw( $url ) );
    exit;
}
