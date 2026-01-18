<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg – Loader
 *
 * Carica tutte le classi del plugin in ordine corretto.
 * Versione aggiornata per architettura:
 * - BuddyBoss Profile Types
 * - WordPress Roles (Members)
 * - Magic Link
 * - Onboarding condizionale
 * - Moduli ComOrg
 */
class ComOrg_Loader {

    public static function init() {

        /**
         * ---------------------------------------------------------
         * 1. DEFINIZIONE COSTANTI
         * ---------------------------------------------------------
         */
        self::define_constants();

        /**
         * ---------------------------------------------------------
         * 2. CARICAMENTO CLASSI CORE
         * ---------------------------------------------------------
         */
        self::load_core_classes();

        /**
         * ---------------------------------------------------------
         * 3. CARICAMENTO SISTEMA DI AUTENTICAZIONE (Magic Link)
         * ---------------------------------------------------------
         */
        require_once COMORG_PATH . 'app/core/classes/class-comorg-auth.php';

        /**
         * ---------------------------------------------------------
         * 4. CARICAMENTO LOGICA PROFILO
         *    (assegnazione Profile Type BuddyBoss)
         * ---------------------------------------------------------
         */
        require_once COMORG_PATH . 'app/core/classes/class-comorg-profile.php';

        /**
         * ---------------------------------------------------------
         * 5. CARICAMENTO FUNZIONI GLOBALI
         * ---------------------------------------------------------
         */
        require_once COMORG_PATH . 'app/core/functions/comorg-helpers.php';
        require_once COMORG_PATH . 'app/core/functions/comorg-hooks.php';

        /**
         * ---------------------------------------------------------
         * 6. CARICAMENTO ADMIN
         * ---------------------------------------------------------
         */
        self::load_admin();

        /**
         * ---------------------------------------------------------
         * 7. CARICAMENTO MODULI COMORG
         * ---------------------------------------------------------
         */
        self::load_modules();

        /**
         * ---------------------------------------------------------
         * 8. CARICAMENTO INTEGRAZIONI
         * ---------------------------------------------------------
         */
        self::load_integrations();

        /**
         * ---------------------------------------------------------
         * 9. INIZIALIZZAZIONE CLASSI CORE
         * ---------------------------------------------------------
         */
        ComOrg_Auth::init();
        ComOrg_Profile::init();
        ComOrg_Groups::init();
        ComOrg_Permissions::init();
        ComOrg_REST::init();
        ComOrg_Install::init();
    }

    /**
     * Definizione costanti globali del plugin
     */
    protected static function define_constants() {
        if ( ! defined( 'COMORG_PATH' ) ) {
            define( 'COMORG_PATH', plugin_dir_path( dirname( dirname( dirname( __FILE__ ) ) ) ) );
        }

        if ( ! defined( 'COMORG_URL' ) ) {
            define( 'COMORG_URL', plugin_dir_url( dirname( dirname( dirname( __FILE__ ) ) ) ) );
        }
    }

    /**
     * Carica le classi core
     */
    protected static function load_core_classes() {

        require_once COMORG_PATH . 'app/core/classes/class-comorg-component.php';
        require_once COMORG_PATH . 'app/core/classes/class-comorg-install.php';
        require_once COMORG_PATH . 'app/core/classes/class-comorg-permissions.php';
        require_once COMORG_PATH . 'app/core/classes/class-comorg-rest.php';
        require_once COMORG_PATH . 'app/core/classes/class-comorg-groups.php';
    }

    /**
     * Carica le classi admin
     */
    protected static function load_admin() {

        if ( is_admin() ) {
            require_once COMORG_PATH . 'app/admin/classes/class-comorg-admin.php';
            require_once COMORG_PATH . 'app/admin/classes/class-comorg-admin-menu.php';
            require_once COMORG_PATH . 'app/admin/classes/class-comorg-admin-settings.php';
            require_once COMORG_PATH . 'app/admin/classes/class-comorg-admin-wizard.php';
        }
    }

    /**
     * Carica i moduli ComOrg
     */
    protected static function load_modules() {

        $modules = array(
            'comorg-gas-orders',
            'comorg-producer-orders',
            'comorg-price-lists',
            'comorg-cashback',
            'comorg-solidarity',
            'comorg-mec',
            'comorg-notifications',
        );

        foreach ( $modules as $module ) {
            $file = COMORG_PATH . "app/modules/{$module}/{$module}.php";
            if ( file_exists( $file ) ) {
                require_once $file;
            }
        }
    }

    /**
     * Carica le integrazioni
     */
    protected static function load_integrations() {

        $integrations = array(
            'woocommerce/comorg-integration-woocommerce.php',
            'buddyboss/comorg-integration-buddyboss.php',
            'charitable/comorg-integration-charitable.php',
            'mec/comorg-integration-mec.php',
            'onesignal/comorg-integration-onesignal.php',
        );

        foreach ( $integrations as $file ) {
            $path = COMORG_PATH . "app/integrations/{$file}";
            if ( file_exists( $path ) ) {
                require_once $path;
            }
        }
    }
}
