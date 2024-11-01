<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\Concerns\InputHelpers;
use TotalForm\Models\Entry;
use TotalForm\Models\FormBlock;
use TotalForm\Models\FormValidationRule;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class MultipleChoices extends BlockType
{
    public static $category = 'field';
    public static $id       = 'multiple-choices-field';
    public static $icon     = 'check_box';

    use InputHelpers;

    /**
     * @param  FormBlock  $block
     *
     * @return Html
     */
    public static function input(FormBlock $block)
    {
        $container = Html::create(
            'div',
            ['v-scope' => '{isOther: false}'],
            [
                Html::create(
                    'input',
                    [
                        'type'  => 'hidden',
                        'name'  => static::getInputName($block->section->uid, $block->uid),
                        'value' => '',
                    ]
                ),
            ]
        );
        $options   = $block->argument('choices', []);

        foreach ($options as $index => $option) {
            $input = Html::create(
                'input',
                [
                    'type'  => 'checkbox',
                    'name'  => static::getInputName($block->section->uid, $block->uid, ''),
                    'value' => $option['uid'],
                    'id'    => "{$block->uid}-{$index}",

                ],
                $option['title'] ?? ''
            );

            $default = $block->argument('defaultValue', false);

            if ($default && $default === $option['uid']) {
                $input->setAttribute('checked');
            }

            $label = Html::create('label', ['class' => 'checkbox'], $input);

            $container->addContent($label);
        }

        if ($block->argument('allowOther')) {
            $label = Html::create('label', ['class' => 'radio']);
            $radio = Html::create(
                'input',
                [
                    '@change' => 'isOther = $el.checked',
                    'type'    => 'checkbox',
                ],
                __('Other', 'totalform')
            );
            $input = Html::create(
                'input',
                [
                    'type'        => 'text',
                    'name'        => static::getInputName($block->section->uid, $block->uid),
                    'placeholder' => __('Other', 'totalform'),
                    'v-if'        => 'isOther',
                    'class'       => 'other-field',
                ]
            );

            $label->addContent($radio)->addContent($input);
            $container->addContent($label);
        }


        return $container;
    }

    public static function getValidationRules(FormBlock $block)
    {
        if (!$block->argument('allowOther')) {
            $block->validations->set(
                'inArray',
                FormValidationRule::from(
                    [
                        'enabled'   => true,
                        'arguments' => [
                            'values' => $block->getChoicesAttribute('uid'),
                        ],
                    ]
                )
            );
        }

        return parent::getValidationRules($block);
    }

    public static function getValidationMessages(FormBlock $formBlock)
    {
        return [
            "{$formBlock->uid}:in_array" => str_replace(
                ['{{allowedValues}}'],
                [implode(', ', $formBlock->getChoicesAttribute('title'))],
                __('Must be one of: {{allowedValues}}', 'totalform')
            ),
        ];
    }

    public static function getSerializedFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        return $value;
    }

    public static function getTextFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        $value   = (array) $value;
        $choices = $block->getChoicesAttribute('title', 'uid');

        foreach ($value as $index => $subValue) {
            if (isset($choices[$subValue])) {
                $value[$index] = esc_html($choices[$subValue]);
            } else {
                $value[$index] = sprintf(__('%s', 'totalform'), esc_html($subValue));
            }
        }

        return implode(', ', (array) $value);
    }

    public static function getMetadataFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        $meta    = ['choices' => []];
        $value   = (array) $value;
        $choices = $block->getChoicesAttribute('title', 'uid');

        foreach ($value as $index => $subValue) {
            if (isset($choices[$subValue])) {
                $meta['choices'][$subValue] = esc_html($choices[$subValue]);
            } else {
                $meta['other']            = true;
                $meta['choices']['other'] = esc_html($subValue);
            }
        }

        return $meta;
    }
}
