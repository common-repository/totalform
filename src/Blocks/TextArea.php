<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\Concerns\InputHelpers;
use TotalForm\Models\FormBlock;
use TotalForm\Models\FormValidationRule;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class TextArea extends BlockType
{
    public static $category = 'field';
    public static $id       = 'textarea-field';
    public static $icon     = 'crop_landscape';

    use InputHelpers;

    public static function input(FormBlock $block)
    {
        $textarea = Html::create(
            'textarea',
            [
                'name'        => static::getInputName($block->section->uid, $block->uid),
                'id'          => static::getInputId($block->uid),
                'rows'        => $block->argument('rows', 3),
                'placeholder' => $block->argument('placeholder'),
            ]
        );

        $textarea->setContent($block->argument('defaultValue'));

        return $textarea;
    }


}
