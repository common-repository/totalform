<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Activation;
! defined( 'ABSPATH' ) && exit();



use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Http\ResponseFactory;
use TotalFormVendors\TotalSuite\Foundation\License;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Activation\UpgradeProduct;

/**
 * Class Upgrade
 *
 * @package TotalSurveyVendors\TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Activation
 */
class Upgrade extends Action
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

    protected function execute()
    {
        $productToUpgrade = $this->request->getParsedBodyParam('product');

        try {
            $license = UpgradeProduct::invoke($productToUpgrade);

            return ResponseFactory::json([
                'message' => __('The product has been successfully upgraded.'),
                'license' => $license
            ]);
        } catch (\Exception $exception) {
            return ResponseFactory::json(['message' => $exception->getMessage(),], 422);
        }
    }

    public function authorize(): bool
    {
        return true;
    }
}