<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\Concerns\InputHelpers;
use TotalForm\Models\FormBlock;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class Date extends BlockType
{
    public static $category = 'field';
    public static $id       = 'date-field';
    public static $icon     = 'calendar_today';

    use InputHelpers;

    public static function input(FormBlock $block)
    {
        $input = Html::create(
            'input',
            [
                'type'        => 'date',
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
