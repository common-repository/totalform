<?php

namespace TotalForm\Capabilities;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\WordPress\Capability;

/**
 * Class UserCanDeleteForm
 *
 * @package TotalForm\Capabilities
 */
class UserCanDeleteForm extends Capability
{
    const NAME = 'totalform_delete_form';
}
