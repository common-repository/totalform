<?php

namespace TotalForm\Capabilities;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\WordPress\Capability;

/**
 * Class UserCanExportEntries
 *
 * @package TotalForm\Capabilities
 */
class UserCanExportEntries extends Capability
{
    const NAME = 'totalform_export_entries';
}
