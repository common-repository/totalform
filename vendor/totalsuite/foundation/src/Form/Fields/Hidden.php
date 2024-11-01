<?php

namespace TotalFormVendors\TotalSuite\Foundation\Form\Fields;
! defined( 'ABSPATH' ) && exit();



/**
 * Class Hidden
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Form\Fields
 */
class Hidden extends Text
{
    /**
     * @var array
     */
    protected $attributes = ['type' => 'hidden'];
}