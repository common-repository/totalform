<?php

namespace TotalForm\Blocks\Concerns;
! defined( 'ABSPATH' ) && exit();



trait InputHelpers
{
    public static function getInputName(...$args)
    {
        $name = array_shift($args) ?? '';

        foreach ($args as $arg) {
            $name .= "[{$arg}]";
        }

        return $name;
    }

    public static function getInputId($uid)
    {
        return "block-input-{$uid}";
    }
}
