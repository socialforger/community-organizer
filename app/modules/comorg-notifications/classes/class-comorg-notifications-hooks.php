<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Notifications – Hooks
 *
 * Collega eventi ComOrg a notifiche automatiche.
 */
class ComOrg_Notifications_Hooks {

    public static function init() {

        // Notifica quando un ordine GAS si chiude
        add_action( 'comorg_gas_order_closed', array( __CLASS__, 'notify_gas_order_closed' ), 10, 2 );

        // Notifica quando un cashback viene aggiunto
        add_action( 'comorg_cashback_added', array( __CLASS__, 'notify_cashback_added' ), 10, 3 );

        // Notifica quando un evento MEC viene sincronizzato
        add_action( 'comorg_mec_event_synced', array( __CLASS__, 'notify_mec_event_synced' ), 10, 2 );
    }

    /**
     * GAS: ordine chiuso
     */
    public static function notify_gas_order_closed( $order_id, $user_id ) {

        $message = sprintf(
            __( 'Il tuo ordine GAS #%d è stato chiuso.', 'comorg' ),
            $order_id
        );

        do_action( 'comorg_notify', $user_id, $message );
    }

    /**
     * Cashback aggiunto
     */
    public static function notify_cashback_added( $user_id, $amount, $description ) {

        $message = sprintf(
            __( 'Hai ricevuto un cashback di €%s. %s', 'comorg' ),
            number_format( $amount, 2, ',', '.' ),
            $description
        );

        do_action( 'comorg_notify', $user_id, $message );
    }

    /**
     * Evento MEC sincronizzato
     */
    public static function notify_mec_event_synced( $event_id, $event_data ) {

        // Notifica agli admin
        $admins = get_users( array( 'role' => 'administrator' ) );

        foreach ( $admins as $admin ) {
            $message = sprintf(
                __( 'Un evento MEC (#%d) è stato sincronizzato con ComOrg.', 'comorg' ),
                $event_id
            );

            do_action( 'comorg_notify', $admin->ID, $message );
        }
    }
}
