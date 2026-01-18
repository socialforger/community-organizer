<?php
/**
 * ComOrg – BuddyBoss Compatibility Integration Class.
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Setup the BuddyBoss compatibility class.
 *
 * @since 1.0.0
 */
class ComOrg_BuddyBoss_Integration extends BP_Integration {

    public function __construct() {

        $this->start(
            'comorg',
            __( 'Community Organizer', 'comorg' ),
            'comorg',
            array(
                'required_plugin' => array(),
            )
        );

        // Add link to settings page.
        add_filter( 'plugin_action_links',               array( $this, 'action_links' ), 10, 2 );
        add_filter( 'network_admin_plugin_action_links', array( $this, 'action_links' ), 10, 2 );
    }

    /**
     * Register admin integration tab
     */
    public function setup_admin_integration_tab() {

        require_once 'comorg-integration-tab.php';

        new ComOrg_BuddyBoss_Admin_Integration_Tab(
            "bp-{$this->id}",
            $this->name,
            array(
                'root_path'       => COMORG_PLUGIN_DIR . 'integration',
                'root_url'        => COMORG_PLUGIN_URL . 'integration',
                'required_plugin' => $this->required_plugin,
            )
        );
    }

    /**
     * Add settings link in plugin list
     */
    public function action_links( $links, $file ) {

        // Return normal links if not ComOrg.
        if ( COMORG_PLUGIN_BASENAME != $file ) {
            return $links;
        }

        // Add a few links to the existing links array.
        return array_merge(
            $links,
            array(
                'settings' => '<a href="' . esc_url(
                    bp_get_admin_url( 'admin.php?page=bp-integrations&tab=bp-comorg' )
                ) . '">' . __( 'Settings', 'comorg' ) . '</a>',
            )
        );
    }
}
