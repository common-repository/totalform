<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\Concerns\InputHelpers;
use TotalForm\Models\Entry;
use TotalForm\Models\FormBlock;
use TotalForm\Models\FormValidationRule;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class Scale extends BlockType
{
    public static $category = 'field';
    public static $id       = 'scale-field';
    public static $icon     = 'linear_scale';

    use InputHelpers;

    /**
     * @param  FormBlock  $block
     *
     * @return Html
     */
    public static function input(FormBlock $block)
    {
        $container = Html::create('');
        $options   = $block->argument('choices', []);

        $max   = $block->argument('scale', 5);
        $least = $block->argument('leastLabel');
        $most  = $block->argument('mostLabel');

        $input = Html::create(
            'div',
            [
                'class'      => 'form-scale',
                'data-least' => $least,
                'data-most'  => $most,
            ]
        );

        $scale = Html::create('div', ['class' => 'scale']);

        for ($currentIndex = 1; $currentIndex <= $max; $currentIndex++) {
            $id    = "{$block->uid}-{$currentIndex}";
            $label = Html::create('label', ['class' => 'scale-step', 'for' => $id], $currentIndex);
            $radio = Html::create(
                'input',
                [
                    'type'  => 'radio',
                    'name'  => static::getInputName($block->section->uid, $block->uid),
                    'value' => $currentIndex,
                    'id'    => $id,

                ],
                $label
            );
            $scale->addContent($radio);
        }

        return $input->addContent($scale);
    }

    public static function getValidationRules(FormBlock $block)
    {
        $block->validations->set(
            'between',
            FormValidationRule::from(
                [
                    'enabled'   => true,
                    'arguments' => [
                        'min' => 1,
                        'max' => $block->getAttribute('arguments.scale', 2),
                    ],
                ]
            )
        );

        return parent::getValidationRules($block);
    }

    public static function getSerializedFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        return +$value;
    }

    public static function getMetadataFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        return [
            'scale' => $block->argument('scale', 0),
        ];
    }

    public static function getTextFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        return sprintf(__('%d out of %d', 'totalform'), (int) $value, $block->argument('scale', 0));
    }
}
