<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\FormBlock;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class Heading extends BlockType
{
    public static $category = 'content';
    public static $id       = 'heading-content';
    public static $icon     = 'title';

    public static function render(FormBlock $block)
    {
        return Html::create(
            'h'.$block->argument('weight', '2'),
            [
                'class' => [
                    "alignment-{$block->argument('alignment', 'start')}",
                ],
            ],
            $block->argument('content')
        );
    }
}
