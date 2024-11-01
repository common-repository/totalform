<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\FormBlock;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class Paragraph extends BlockType
{
    public static $category = 'content';
    public static $id       = 'paragraph-content';

    public static function render(FormBlock $block)
    {
        return Html::create(
            'p',
            [
                'class' => [
                    "alignment-{$block->argument('alignment', 'start')}",
                ],
            ],
            $block->argument('content')
        );
    }
}
