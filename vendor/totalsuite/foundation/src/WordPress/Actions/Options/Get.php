<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Options;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Http\Response;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Options;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Options\GetOptions;

class Get extends Action
{
    /**
     * @var Options
     */
    protected $options;

    /**
     * ResetOptions constructor.
     *
     * @param Options $options
     */
    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function execute(): Response
    {
        return GetOptions::invoke($this->options)->toJsonResponse();
    }

    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }
}
