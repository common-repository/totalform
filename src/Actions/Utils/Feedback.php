<?php

namespace TotalForm\Actions\Utils;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Tasks\Utils\StoreFeedback;
use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Http\Response;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Options;

class Feedback extends Action
{
    /**
     * @var Options
     */
    protected $options;

    /**
     * ResetOptions constructor.
     *
     * @param  Options  $options
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
        return StoreFeedback::invoke($this->options, $data)->toJsonResponse();
    }

    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }
}
