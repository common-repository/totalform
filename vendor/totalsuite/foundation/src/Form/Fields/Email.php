<?php

namespace TotalFormVendors\TotalSuite\Foundation\Form\Fields;
! defined( 'ABSPATH' ) && exit();


/**
 * Class Email
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Form\Fields
 */
class Email extends Text
{
    /**
     * @var array
     */
    protected $attributes = ['type' => 'email'];
}