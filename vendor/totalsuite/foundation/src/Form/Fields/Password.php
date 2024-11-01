<?php

namespace TotalFormVendors\TotalSuite\Foundation\Form\Fields;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

/**
 * Class Password
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Form\Fields
 */
class Password extends Text
{
    /**
     * @var array
     */
    protected $attributes = ['type' => 'password'];

    /**
     * @return Html
     */
    public function toHTML()
    {
        $html = parent::toHTML();
        $html->removeAttribute('value');

        return $html;
    }


}