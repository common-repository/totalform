<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Activation;
! defined( 'ABSPATH' ) && exit();



use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Http\ResponseFactory;
use TotalFormVendors\TotalSuite\Foundation\License;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Plugin;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Activation\ActivateLicense;


/**
 * Class Activate
 *
 * @package TotalSurveyVendors\TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Activation
 */
class Activate extends Action
{
    /**
     * @var License
     */
    protected $license;

    /**
     * Create constructor.
     *
     * @param License $license
     */
    public function __construct(License $license)
    {
        $this->license = $license;
    }


    /**
     * @throws Exception
     */
    protected function execute()
    {
        if ($this->license->isRegistered()) {
            return ResponseFactory::json([
                'message' => __('You have already activated your copy.'),
                'license' => $this->license
            ]);
        }

        $key = (string)$this->request->getParsedBodyParam('key');
        $host = Plugin::env()->hostName();
        $product = Plugin::env()->get('product.id');

        try {
            $license = ActivateLicense::invoke($key, $host, $product);

            return ResponseFactory::json(
                [
                    'message' => __('You have successfully activated your copy.'),
                    'license' => $license
                ]
            );
        } catch (\Exception $exception) {
            return ResponseFactory::json(
                [
                    'message' => $exception->getMessage(),
                ],
                422
            );
        }
    }

    public function authorize(): bool
    {
        return true;
    }
}