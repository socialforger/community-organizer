<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Solidarity – Database Handler
 *
 * Registra movimenti di solidarietà (crediti, aiuti, fondi).
 */
class ComOrg_Solidarity_DB {

    protected static $table = 'comorg_solidarity';

    public static function init() {
        add_action( 'plugins_loaded', array( __CLASS__, 'maybe_create_table' ) );
    }

    /**
     * Crea tabella DB se mancante
     */
    public static function maybe_create_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . self::$table;
        $charset    = $wpdb->get_charset_collate();

        $sql = "
            CREATE TABLE IF NOT EXISTS {$table_name} (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                user_id BIGINT UNSIGNED NOT NULL,
                amount DECIMAL(10,2) NOT NULL DEFAULT 0,
                type VARCHAR(50) NOT NULL,
                description TEXT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY user_id (user_id)
            ) {$charset};
        ";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    /**
     * Aggiunge un movimento di solidarietà
     */
    public static function add( $user_id, $amount, $type, $description = '' ) {
        global $wpdb;

        return $wpdb->insert(
            $wpdb->prefix . self::$table,
            array(
                'user_id'     => $user_id,
                'amount'      => $amount,
                'type'        => sanitize_text_field( $type ),
                'description' => $description,
            ),
            array( '%d', '%f', '%s', '%s' )
        );
    }

    /**
     * Recupera movimenti per utente
     */
    public static function get_by_user( $user_id ) {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}" . self::$table . " WHERE user_id = %d ORDER BY created_at DESC",
                $user_id
            )
        );
    }
}
