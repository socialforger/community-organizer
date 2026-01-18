<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Price Lists – Parser
 *
 * Gestisce parsing e validazione dei listini (CSV, XLSX, JSON).
 */
class ComOrg_Price_Parser {

    public static function init() {
        // Hook se necessario
    }

    /**
     * Parse CSV file
     */
    public static function parse_csv( $file_path ) {

        if ( ! file_exists( $file_path ) ) {
            return array();
        }

        $rows = array();
        $handle = fopen( $file_path, 'r' );

        if ( ! $handle ) {
            return array();
        }

        $header = null;

        while ( ( $data = fgetcsv( $handle, 0, ';' ) ) !== false ) {

            if ( ! $header ) {
                $header = $data;
                continue;
            }

            $rows[] = array_combine( $header, $data );
        }

        fclose( $handle );

        return $rows;
    }

    /**
     * Validate parsed list
     */
    public static function validate( $rows ) {

        $valid = array();

        foreach ( $rows as $row ) {

            if ( empty( $row['sku'] ) || empty( $row['price'] ) ) {
                continue;
            }

            $valid[] = array(
                'sku'   => sanitize_text_field( $row['sku'] ),
                'name'  => sanitize_text_field( $row['name'] ?? '' ),
                'price' => floatval( $row['price'] ),
                'unit'  => sanitize_text_field( $row['unit'] ?? '' ),
            );
        }

        return $valid;
    }
}
