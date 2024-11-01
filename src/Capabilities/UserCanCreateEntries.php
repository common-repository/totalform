<?php

namespace TotalForm\Capabilities;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\WordPress\Capability;

/**
 * Class UserCanCreateForm
 *
 * @package TotalForm\Capabilities
 */
class UserCanCreateEntries extends Capability
{
    const NAME = 'totalform_create_entries';
}
