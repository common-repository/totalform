<?php

namespace TotalForm\Filters\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\WordPress\Filter;

/**
 * Class FormAfterEntryViewFilter
 * @method static string apply(string $content)
 *
 * @package TotalForm\Filters
 */
class FormAfterEntryViewFilter extends Filter
{

    protected static function alias()
    {
        return 'totalform/forms/after-entry-view';
    }
}
