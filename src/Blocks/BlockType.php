<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Entry;
use TotalForm\Models\EntryBlock;
use TotalForm\Models\EntrySection;
use TotalForm\Models\FormBlock;
use TotalForm\Services\BlockRegistry;
use TotalForm\Validations\DefaultValidationRules;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

abstract class BlockType
{
    public static $category = 'default';
    public static $id       = 'default';
    public static $icon     = 'subject';

    /**
     * @return string
     */
    public static function getId()
    {
        return sprintf("%s:%s", static::$category, static::$id);
    }

    /**
     * @throws \TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception
     */
    public static function register()
    {
        BlockRegistry::register(static::class);
    }

    /**
     * @param  FormBlock  $block
     *
     * @return array
     */
    public static function getValidationRules(FormBlock $block)
    {
        $rules       = [$block->uid => []];
        $validations = $block->getEnabledValidations();

        foreach ($validations as $name => $validation) {
            $rules[$block->uid][] = DefaultValidationRules::from($name, $validation);
        }

        return $rules;
    }

    /**
     * @param  FormBlock  $formBlock
     *
     * @return array
     */
    public static function getValidationMessages(FormBlock $formBlock)
    {
        return [];
    }

    /**
     * @param  FormBlock  $block
     *
     * @return array
     */
    public static function getValidationAliases(FormBlock $block)
    {
        return [$block->uid => empty($block->label) ? __('answer', 'totalform') : $block->label];
    }

    /**
     * @param  EntrySection  $entrySection
     * @param  FormBlock  $block
     * @param  Entry  $entry
     * @param  mixed  $value
     *
     * @return EntryBlock
     */
    public static function getEntryBlockFromRawValue($value, FormBlock $block, EntrySection $entrySection, Entry $entry)
    {
        return new EntryBlock(
            $entrySection,
            [
                'uid'   => $block->uid,
                'title' => $block->title,
                'value' => static::getSerializedFromRawValue($block, $entry, $value),
                'text'  => static::getTextFromRawValue($block, $entry, $value),
                'meta'  => static::getMetadataFromRawValue($block, $entry, $value),
                'type'  => $block->type,
            ]
        );
    }

    /**
     * @param  FormBlock  $block
     * @param  Entry  $entry
     * @param $value
     *
     * @return mixed
     */
    public static function getSerializedFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        return static::getTextFromRawValue($block, $entry, $value);
    }

    /**
     * @param  FormBlock  $block
     * @param  Entry  $entry
     * @param $value
     *
     * @return string
     */
    public static function getTextFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        return implode(', ', array_map('esc_html', (array) $value));
    }

    /**
     * @param  FormBlock  $block
     * @param  Entry  $entry
     * @param $value
     *
     * @return array
     */
    public static function getMetadataFromRawValue(FormBlock $block, Entry $entry, $value)
    {
        return [];
    }

    /**
     * @param  FormBlock  $block
     *
     * @return null
     */
    public static function render(FormBlock $block)
    {
        return Html::create('div', ['class' => ['block-input-group']])
                   ->addContent(static::label($block))
                   ->addContent(static::input($block));
    }

    /**
     * @param  FormBlock  $block
     *
     * @return Html
     */
    public static function input(FormBlock $block)
    {
        return null;
    }

    /**
     * @param  FormBlock  $block
     *
     * @return Html
     */
    public static function label(FormBlock $block)
    {
        return Html::create(
            'label',
            [
                'for'   => "block-input-{$block->uid}",
                'class' => $block->argument('validations.required.enabled') ? 'required' : '',
            ],
            $block->title
        );
    }
}
