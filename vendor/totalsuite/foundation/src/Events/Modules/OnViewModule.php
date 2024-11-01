<?php


namespace TotalFormVendors\TotalSuite\Foundation\Events\Modules;
! defined( 'ABSPATH' ) && exit();



use TotalFormVendors\TotalSuite\Foundation\Event;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Definition;

class OnViewModule extends Event {
    /**
     * @var Definition
     */
    public $definition;

    /**
     * OnActivateModule constructor.
     *
     * @param  Definition  $definition
     */
    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

}