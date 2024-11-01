<?php

namespace TotalFormVendors\TotalSuite\Foundation\Form\Fields;
! defined( 'ABSPATH' ) && exit();


/**
 * Class Tel
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Form\Fields
 */
class Tel extends Text
{
    /**
     * @var array
     */
    protected $attributes = ['type' => 'tel'];
}