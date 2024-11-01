<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Options;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Http\Response;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Options;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Options\ResetOptions;

class Reset extends Action
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
     */
    public function execute(): Response
    {
        return ResetOptions::invoke($this->options)->toJsonResponse();
    }

    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }
}
