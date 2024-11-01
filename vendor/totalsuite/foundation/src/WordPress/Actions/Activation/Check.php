<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Activation;
! defined( 'ABSPATH' ) && exit();



use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Http\Response;
use TotalFormVendors\TotalSuite\Foundation\Http\ResponseFactory;
use TotalFormVendors\TotalSuite\Foundation\License;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Activation\CheckLicense as CheckLicenseTask;

class Check extends Action
{

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return Response
     */
    protected function execute()
    {
        try {
            CheckLicenseTask::invoke();
        } catch (Exception $exception) {
            ResponseFactory::json(
                [
                    'message' => $exception->getMessage(),
                    'license' => License::instance()
                ],
                422
            );
        }

        return ResponseFactory::json(['license' => License::instance()]);
    }
}