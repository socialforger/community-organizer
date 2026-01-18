
<?php
defined( 'ABSPATH' ) || exit;

/**
 * Base Component (simile a BP_Component)
 */
abstract class ComOrg_Component {

    public $id = '';
    public $name = '';

    public function __construct( $id, $name ) {
        $this->id   = $id;
        $this->name = $name;

        $this->setup_hooks();
    }

    abstract protected function setup_hooks();
}
