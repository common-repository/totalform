<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\FormBlock;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class Spacer extends BlockType
{
    public static $category = 'content';
    public static $id       = 'spacer-content';
    public static $icon     = 'exapnd';

    public static function render(FormBlock $block)
    {
        $height = $block->argument('height');
        $unit   = $block->argument('unit');

        return Html::create(
            'div',
            [
                'class' => [],
                'style' => "height: {$height}{$unit}",
            ]
        );
    }
}
