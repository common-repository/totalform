<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\Concerns\InputHelpers;
use TotalForm\Models\FormBlock;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class Hidden extends BlockType
{
    public static $category = 'field';
    public static $id       = 'hidden-field';
    public static $icon     = 'crop_7_5';

    use InputHelpers;

    public static function input(FormBlock $block)
    {
        $input = Html::create(
            'input',
            [
                'type' => 'hidden',
                'name' => static::getInputName($block->section->uid, $block->uid),
                'id' => static::getInputId($block->uid),
                'slug' => $block->getSlug(),
                'placeholder' => $block->argument('placeholder'),
                'value' => $block->argument('defaultValue'),
            ]
        );

        return $input;
    }

    public static function label(FormBlock $block)
    {
        return null;
    }
}
