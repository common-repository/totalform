<?php

namespace TotalFormVendors\TotalSuite\Foundation;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\League\Event\Emitter as AbstractEmitter;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Concerns\ResolveFromContainer;

/**
 * Class Emitter
 *
 * @package TotalSuite\Foundation
 */
class Emitter extends AbstractEmitter
{
    use ResolveFromContainer;
}