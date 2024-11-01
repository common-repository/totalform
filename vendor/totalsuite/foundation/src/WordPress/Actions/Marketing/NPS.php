<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Marketing;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Http\Response;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Options;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Marketing\StoreNPS;

class NPS extends Action
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
        $this->options = $options->withKey('marketing');
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function execute(): Response
    {
        $data = $this->request->getParsedBody();
        return StoreNPS::invoke($this->options, $data)->toJsonResponse();
    }

    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }
}
