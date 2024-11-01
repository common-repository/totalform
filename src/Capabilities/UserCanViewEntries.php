<?php

namespace TotalForm\Capabilities;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\WordPress\Capability;

/**
 * Class UserCanViewEntries
 *
 * @package TotalForm\Capabilities
 */
class UserCanViewEntries extends Capability
{
    const NAME = 'totalform_view_entries';
}
