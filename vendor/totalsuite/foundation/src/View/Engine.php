<?php

namespace TotalFormVendors\TotalSuite\Foundation\View;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Helpers\Concerns\ResolveFromContainer;

/**
 * Class Engine
 *
 * @package TotalSuite\Foundation
 */
class Engine extends \TotalFormVendors\League\Plates\Engine
{
    use ResolveFromContainer;
}
