<?php
defined( 'ABSPATH' ) || exit;

/**
 * ComOrg Solidarity – Hooks
 *
 * Collega eventi ComOrg ai movimenti solidali.
 */
class ComOrg_Solidarity_Hooks {

    public static function init() {

        // Esempio: quando un ordine GAS viene chiuso → contributo solidarietà
        add_action( 'comorg_gas_order_closed', array( __CLASS__, 'on_gas_order_closed' ), 10, 2 );

        // Esempio: quando un cashback viene aggiunto → quota solidarietà
        add_action( 'comorg_cashback_added', array( __CLASS__, 'on_cashback_added' ), 10, 3 );
    }

    /**
     * GAS: ordine chiuso → contributo solidarietà
     */
    public static function on_gas_order_closed( $order_id, $user_id ) {

        $amount = 1.00; // esempio: contributo fisso

        do_action(
            'comorg_solidarity_add',
            $user_id,
            $amount,
            'gas_contribution',
            __( 'Contributo solidarietà da ordine GAS', 'comorg' )
        );
    }

    /**
     * Cashback → quota solidarietà
     */
    public static function on_cashback_added( $user_id, $amount, $description ) {

        // esempio: 10% del cashback va nel fondo solidarietà
        $solidarity_amount = $amount * 0.10;

        do_action(
            'comorg_solidarity_add',
            $user_id,
            $solidarity_amount,
            'cashback_share',
            __( 'Quota solidarietà dal cashback', 'comorg' )
        );
    }
}
