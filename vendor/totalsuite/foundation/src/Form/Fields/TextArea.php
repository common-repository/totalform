<?php

namespace TotalFormVendors\TotalSuite\Foundation\Form\Fields;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Form\Field;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;


/**
 * Class TextArea
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Form\Fields
 */
class TextArea extends Field
{
    /**
     * @return Html
     */
    public function toHTML()
    {
        return Html::create('textarea', $this->getHtmlAttributes(['value']), $this->getValue());
    }
}