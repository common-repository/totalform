<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\Concerns\InputHelpers;
use TotalForm\Models\Entry;
use TotalForm\Models\FormBlock;
use TotalForm\Models\FormValidationRule;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class Dropdown extends BlockType
{
    public static $category = 'field';
    public static $id       = 'dropdown-field';
    public static $icon     = 'list';

    use InputHelpers;

    /**
     * @param  FormBlock  $block
     *
     * @return Html
     */
    public static function input(FormBlock $block)
    {
        $options = $block->argument('choices', []);
        $select  = Html::create(
            'select',
            [
                'name'    => static::getInputName($block->section->uid, $block->uid),
                'id'      => static::getInputId($block->uid),
                '@change' => 'isOther = $el.value === \'other\'',
            ],
            Html::create('option', ['value' => ''], $block->argument('placeholder') ?: __('Choose', 'totalform'))
        );

        if ($block->argument('multiple', false) === true) {
            $select->setAttribute('multiple');
        }

        $fromRequest = filter_var($_GET[$block->title] ?? $_GET[$block->getSlug()] ?? $_GET[$block->uid] ?? '', FILTER_SANITIZE_STRING);

        $defaultValue = $block->argument(
            'defaultValue',
            $fromRequest
        );

        foreach ($options as $option) {
            $optionTag = Html::create('option', ['value' => $option['uid']], $option['title']);

            if (!empty($defaultValue) && ($option['uid'] === $defaultValue || $option['title'] === $defaultValue)) {
                $optionTag->setAttribute('selected');
            }

            $select->addContent($optionTag);
        }

        if ($block->argument('allowOther')) {
            $otherOptionTag = Html::create('option', ['value' => 'other'], __('Other', 'totalform'));
            $select->addContent($otherOptionTag);

            $otherInput = Html::create(
                'input',
                [
                    'type'        => 'text',
                    'name'        => $select->getAttribute('name'),
                    'placeholder' => __('Other', 'totalform'),
                    'v-if'        => 'isOther',
                    'class'       => 'other-field',
                ]
            );

            return Html::create('div', ['v-scope' => '{isOther: false}'], [$select, $otherInput]);
        }

        return $select;
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
}
