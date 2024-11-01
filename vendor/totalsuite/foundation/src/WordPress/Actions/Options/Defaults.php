<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Options;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Http\Response;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Options;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Options\DefaultOptions;

class Defaults extends Action
{
    /**
     * @var Options
     */
    protected $options;

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
        return DefaultOptions::invoke($this->options)->toJsonResponse();
    }

    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }
}
