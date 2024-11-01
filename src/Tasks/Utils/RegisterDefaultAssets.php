<?php

namespace TotalForm\Tasks\Utils;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Plugin;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class RegisterDefaultAssets
 *
 * @package TotalForm\Tasks
 * @method static array invoke()
 * @method static array invokeWithFallback(array $fallback)
 */
class RegisterDefaultAssets extends Task
{
    /**
     * @inheritDoc
     */
    protected function validate()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function execute()
    {
        $baseUrl          = Plugin::env('url.base');
        $version          = Plugin::env('version');

        wp_enqueue_script(
            'petite-vue',
            "{$baseUrl}/assets/vendor/petite-vue.iife.js",
            ['jquery'],
            '0.4.1',
            true
        );
    }
}
