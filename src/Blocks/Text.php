<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\Concerns\InputHelpers;
use TotalForm\Models\FormBlock;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class Text extends BlockType
{
    public static $category = 'field';
    public static $id       = 'text-field';
    public static $icon     = 'crop_7_5';

    use InputHelpers;

    public static function input(FormBlock $block)
    {
        $input = Html::create(
            'input',
            [
                'type'        => 'text',
                'name'        => static::getInputName($block->section->uid, $block->uid),
                'id'          => static::getInputId($block->uid),
                'slug'        => $block->getSlug(),
                'placeholder' => $block->argument('placeholder'),
                'value'       => $block->argument('defaultValue'),
            ]
            + ($block->argument('validations.required.enabled') ? ['required'] : [])
        );

        return $input;
    }
}
