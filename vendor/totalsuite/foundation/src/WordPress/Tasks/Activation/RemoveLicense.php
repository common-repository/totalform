<?php


namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Activation;
! defined( 'ABSPATH' ) && exit();



use TotalFormVendors\TotalSuite\Foundation\License;
use TotalFormVendors\TotalSuite\Foundation\Task;

class RemoveLicense extends Task
{
    protected function validate()
    {
    }

    protected function execute()
    {
        License::instance()->reset();
    }
}