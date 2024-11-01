<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\FormBlock;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class Image extends BlockType
{
    public static $category = 'content';
    public static $id       = 'image-content';
    public static $icon     = 'photo';

    public static function render(FormBlock $block)
    {
        $figure = Html::create(
            'figure',
            [
                'class' => [
                    "alignment-{$block->argument('alignment', 'start')}",
                ],
            ]
        );

        $image = Html::create(
            'img',
            [
                'src' => esc_url($block->argument('url')),
                'alt' => $block->argument('alt'),
            ]
        );

        $caption = $block->argument('caption');

        if ($caption) {
            $caption = Html::create('figcaption', [], $caption);
        }

        $figure->addContent($image);
        $figure->addContent($caption);

        return $figure;
    }
}
