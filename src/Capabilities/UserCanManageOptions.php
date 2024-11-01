<?php


namespace TotalForm\Capabilities;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\WordPress\Capability;

/**
 * Class UserCanManageOptions
 *
 * @package TotalForm\Capabilities
 */
class UserCanManageOptions extends Capability
{
    const NAME = 'totalform_manage_options';
}
