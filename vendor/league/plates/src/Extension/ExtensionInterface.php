<?php

namespace TotalFormVendors\League\Plates\Extension;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\League\Plates\Engine;

/**
 * A common interface for extensions.
 */
interface ExtensionInterface
{
    public function register(Engine $engine);
}
