<?php
defined( 'ABSPATH' ) || exit;

function comorg_log( $msg ) {
    if ( WP_DEBUG ) {
        error_log( '[ComOrg] ' . print_r( $msg, true ) );
    }
}
