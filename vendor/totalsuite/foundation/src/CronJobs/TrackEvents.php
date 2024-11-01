<?php

namespace TotalFormVendors\TotalSuite\Foundation\CronJobs;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Contracts\TrackingStorage;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Plugin;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Scheduler;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Scheduler\CronJob;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Tracking\SendTrackingRequest;

class TrackEvents extends CronJob
{
    /**
     * @throws Exception|Exception
     */
    public function execute()
    {
        $url = Plugin::env('url.tracking.events');
        $options = Plugin::get(TrackingStorage::class);

        SendTrackingRequest::invoke($url, $options->all());
    }

    public function getRecurrence()
    {
        return Scheduler::SCHEDULE_DAILY;
    }

    public function getStartTime()
    {
        return time();
    }
}