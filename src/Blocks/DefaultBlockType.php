<?php

namespace TotalForm\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\FormBlock;

class DefaultBlockType extends BlockType
{
    public static $category = 'default';
    public static $id       = 'default';
    public static $icon     = 'subject';

    public static function render(FormBlock $block)
    {
        return '';
    }
}
