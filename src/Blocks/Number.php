<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\Concerns\InputHelpers;
use TotalForm\Models\Entry;
use TotalForm\Models\FormBlock;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class Number extends BlockType
{
    public static $category = 'field';
    public static $id       = 'number-field';
    public static $icon     = 'looks_one';

    use InputHelpers;

    public static function input(FormBlock $block)
    {
        $input = Html::create(
            'input',
            [
                'type'        => 'number',
                'name'        => static::getInputName($block->section->uid, $block->uid),
                'id'          => static::getInputId($block->uid),
                'slug'        => $block->getSlug(),
                'placeholder' => $block->argument('placeholder'),
                'value'       => $block->argument('defaultValue'),
            ]
            + ($block->argument('validations.required.enabled') ? ['required'] : [])
            + ($block->argument('validations.min.enabled') ? [
                'min' => $block->argument(
                    'validations.min.arguments.value'
                ),
            ] : [])
            + ($block->argument('validations.max.enabled') ? [
                'max' => $block->argument(
                    'validations.max.arguments.value'
                ),
            ] : [])
        );

        return $input;
    }

    public static function getSerializedFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        return is_numeric($value) ? +$value : $value;
    }
}
