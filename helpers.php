<?php
! defined( 'ABSPATH' ) && exit();


use TotalForm\Plugin;

if (!function_exists('TotalForm')) {
    /**
     * @return Plugin
     */
    function TotalForm()
    {
        return TotalForm\Plugin::instance();
    }
}
