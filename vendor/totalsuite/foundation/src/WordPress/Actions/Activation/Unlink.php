<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Activation;
! defined( 'ABSPATH' ) && exit();



use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Http\ResponseFactory;
use TotalFormVendors\TotalSuite\Foundation\License;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Activation\RemoveLicense as RemoveLicenseTask;

class Unlink extends Action
{
    public function authorize(): bool
    {
        return true;
    }

    protected function execute()
    {
        $license = License::instance();

        try {
            RemoveLicenseTask::invoke();
        } catch (Exception $exception) {
            ResponseFactory::json([
                'message' => $exception->getMessage(),
                'license' => $license
            ], 422);
        }

        return ResponseFactory::json($license);
    }
}