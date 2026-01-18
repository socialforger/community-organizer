<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Cashback – Database Handler
 */
class ComOrg_Cashback_DB {

    protected static $instance = null;
    protected static $table = 'comorg_cashback';

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function init() {
        add_action( 'plugins_loaded', array( __CLASS__, 'maybe_create_table' ) );
    }

    /**
     * Create DB table if missing
     */
    public static function maybe_create_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . self::$table;

        $charset = $wpdb->get_charset_collate();

        $sql = "
            CREATE TABLE IF NOT EXISTS {$table_name} (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                user_id BIGINT UNSIGNED NOT NULL,
                amount DECIMAL(10,2) NOT NULL DEFAULT 0,
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
     * Insert cashback entry
     */
    public static function add( $user_id, $amount, $description = '' ) {
        global $wpdb;

        return $wpdb->insert(
            $wpdb->prefix . self::$table,
            array(
                'user_id'     => $user_id,
                'amount'      => $amount,
                'description' => $description,
            ),
            array( '%d', '%f', '%s' )
        );
    }

    /**
     * Get cashback entries for a user
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
