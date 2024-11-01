<?php

namespace TotalForm\Tasks\Utils;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Plugin;
use TotalFormVendors\TotalSuite\Foundation\Database\Connection;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\DatabaseException;
use TotalFormVendors\TotalSuite\Foundation\Task;

class WipeFormsData extends Task
{
    protected function validate()
    {
        return true;
    }

    protected function execute()
    {
        if (!defined('TOTALFORM_UNINSTALLING')) {
            return;
        }

        $db = Plugin::instance()->container(Connection::class);

        // Delete plugin options
        delete_option(Plugin::env('stores.optionsKey'));
        delete_option(Plugin::env('stores.modulesKey'));
        delete_option(Plugin::env('stores.versionKey'));
        delete_option(Plugin::env('stores.trackingKey'));

        try {
            $db->raw(sprintf('DROP TABLE %stotalform_forms', $db->getTablePrefix()));
            $db->raw(sprintf('DROP TABLE %stotalform_entries', $db->getTablePrefix()));
        } catch (DatabaseException $e) {
            var_dump($e);
        }
    }
}
