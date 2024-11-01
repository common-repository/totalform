<?php

namespace TotalFormVendors\TotalSuite\Foundation\Form\Fields;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Form\Field;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

/**
 * Class Text
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Form\Fields
 */
class Text extends Field
{
    /**
     * @var array
     */
    protected $attributes = ['type' => 'text'];

    /**
     * @return Html
     */
    public function toHTML()
    {
        return Html::create('input', $this->getHtmlAttributes());
    }
}