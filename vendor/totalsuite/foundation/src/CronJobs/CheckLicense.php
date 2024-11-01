<?php

namespace TotalFormVendors\TotalSuite\Foundation\CronJobs;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Scheduler;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Scheduler\CronJob;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Activation\CheckLicense as CheckLicenseTask;

/**
 * Class CheckLicense
 *
 * @package TotalFormVendors\TotalSuite\Foundation\CronJobs
 */
class CheckLicense extends CronJob
{
    public function execute()
    {
        try {
            CheckLicenseTask::invoke();
        } catch (Exception $e) {
            return;
        }
    }

    public function getRecurrence()
    {
        return Scheduler::SCHEDULE_DAILY;
    }
}