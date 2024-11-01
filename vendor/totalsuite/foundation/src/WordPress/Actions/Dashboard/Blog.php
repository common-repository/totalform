<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Dashboard;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Http\Response;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\GetBlogFeed;

class Blog extends Action
{
    /**
     * @return Response
     * @throws Exception
     */
    public function execute(): Response
    {
        return GetBlogFeed::invoke()->toJsonResponse();
    }

    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }
}