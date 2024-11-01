<?php

namespace TotalForm\Capabilities;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\WordPress\Capability;

/**
 * Class UserCanManageModules
 *
 * @package TotalForm\Capabilities
 */
class UserCanManageModules extends Capability
{
    const NAME = 'totalform_manage_modules';
}
