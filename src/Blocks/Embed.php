<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\FormBlock;
use TotalFormVendors\TotalSuite\Foundation\Helpers\Html;

class Embed extends BlockType
{
    public static $category = 'content';
    public static $id       = 'embed-content';
    public static $icon     = 'cloud';

    public static function render(FormBlock $block)
    {
        $embed = $block->argument('content');

        if (wp_http_validate_url($embed)) {
            $embed = $GLOBALS['wp_embed']->run_shortcode("[embed]{$embed}[/embed]");
        }

        return Html::create(
            'div',
            [
                'class' => [
                    "aspect-ratio aspect-ratio-{$block->argument('aspectRatio', 'original')}",
                ],
            ],
            $embed
        );
    }
}
