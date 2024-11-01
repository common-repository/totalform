<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\Concerns\InputHelpers;
use TotalForm\Models\Entry;
use TotalForm\Models\EntrySection;
use TotalForm\Models\FormBlock;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;
use TotalFormVendors\TotalSuite\Foundation\Support\Arrays;

class Rating extends BlockType
{
    public static $category = 'field';
    public static $id       = 'star-rating-field';
    public static $icon     = 'linear_scale';
    public static $symbols  = [
        'faces'  => ['🙁', '😐', '🙂', '😀', '😍'],
        'stars'  => ['️⭐️', '️⭐️', '️⭐️', '️⭐️', '️⭐️'],
        'hearts' => ['❤️', '❤️', '❤️', '❤️', '❤️'],
    ];

    use InputHelpers;

    public static function input(FormBlock $block)
    {
        $list = Html::create(
            'div',
            ['class' => 'form-rating',]
        );

        $symbols = static::$symbols[$block->argument('symbol', 'stars')];

        $items = [];
        foreach ($symbols as $value => $icon) {
            $id = "{$block->uid}-{$value}";

            $item  = Html::create(
                'label',
                ['class' => 'form-rating-item', 'for' => $id],
                Html::create('span', [], $icon)
            );
            $input = Html::create(
                'input',
                [
                    'type'        => 'radio',
                    'name'        => static::getInputName($block->section->uid, $block->uid),
                    'id'          => $id,
                    'placeholder' => $block->argument('placeholder'),
                    'value'       => $value + 1,
                ]
            );

            $items[] = $item;
            $items[] = $input;
        }

        $list->addContent(array_reverse($items));

        return $list;
    }

    public static function getEntryBlockFromRawValue($value, FormBlock $block, EntrySection $entrySection, Entry $entry)
    {
        $entryBlock = parent::getEntryBlockFromRawValue($value, $block, $entrySection, $entry);

        $symbol            = $block->argument('symbol', 'stars');
        $entryBlock->value = (int) $value;
        $entryBlock->text  = static::getSymbolLabel($symbol, $value);

        $entryBlock->meta->set('value', static::getSymbolValue($symbol, $value));
        $entryBlock->meta->set('symbol', $symbol);

        return $entryBlock;
    }

    protected static function getSymbolValue($symbol, $value)
    {
        if ($symbol === 'faces') {
            return Arrays::get(static::$symbols[$symbol], $value - 1, '');
        }

        return implode(' ', array_slice(static::$symbols[$symbol], 0, $value));
    }

    protected static function getSymbolLabel($symbol, $value)
    {
        $labels = [
            'faces'  => [
                __('Very Sad', 'totalform'),
                __('Sad', 'totalform'),
                __('Neutral', 'totalform'),
                __('Happy', 'totalform'),
                __('Very Happy', 'totalform'),
            ],
            'hearts' => _n('%d heart', '%d hearts', $value),
            'stars'  => _n('%d star', '%d stars', $value),
        ];

        if ($symbol === 'faces') {
            return $labels[$symbol][$value - 1] ?? '';
        }

        return sprintf($labels[$symbol], $value);
    }
}
