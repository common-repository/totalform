<?php

namespace TotalForm\Capabilities;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\WordPress\Capability;

/**
 * Class UserCanDeleteEntries
 *
 * @package TotalForm\Capabilities
 */
class UserCanDeleteEntries extends Capability
{
    const NAME = 'totalform_delete_entries';
}
