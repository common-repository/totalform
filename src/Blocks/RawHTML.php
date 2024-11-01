<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\FormBlock;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class RawHTML extends BlockType
{
    public static $category = 'content';
    public static $id       = 'html-content';
    public static $icon     = 'code';

    public static function render(FormBlock $block)
    {
        $content = $block->argument('content');

        return Html::create(
            'div',
            [
                'class' => [],
            ],
            $block->argument('evaluateShortcodes') ? do_shortcode($content, true) : $content
        );
    }
}
