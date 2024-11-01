<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\League\Event\EventInterface;
use TotalFormVendors\TotalSuite\Foundation\Event;
use TotalFormVendors\TotalSuite\Foundation\Listener;

/**
 * Class ActionBus
 *
 * @package TotalFormVendors\TotalSuite\Foundation\WordPress
 */
class ActionBus extends Listener
{
    /**
     * Handle an event.
     *
     * @param EventInterface $event
     *
     * @return void
     */
    public function handle(EventInterface $event)
    {
        if (!$event->isPropagationStopped()) {
            do_action($event instanceof Event ? $event::alias() : $event->getName(), $event);
        }
    }
}