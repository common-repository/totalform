<?php

namespace TotalFormVendors\TotalSuite\Foundation\Form\Fields;
! defined( 'ABSPATH' ) && exit();



/**
 * Class Number
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Form\Fields
 */
class Number extends Text
{
    /**
     * @var array
     */
    protected $attributes = ['type' => 'number'];
}