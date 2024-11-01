<?php

namespace TotalForm\Capabilities;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\WordPress\Capability;

/**
 * Class UserCanUpdateForm
 *
 * @package TotalForm\Capabilities
 */
class UserCanUpdateEntries extends Capability
{
    const NAME = 'totalform_update_entries';
}
