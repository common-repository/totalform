<?php

namespace TotalForm\Tasks\Utils;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Task;

class FlushRewriteRules extends Task
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
        delete_option('rewrite_rules');
    }
}
