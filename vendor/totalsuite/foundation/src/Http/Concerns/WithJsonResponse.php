<?php


namespace TotalFormVendors\TotalSuite\Foundation\Http\Concerns;
! defined( 'ABSPATH' ) && exit();



use TotalFormVendors\TotalSuite\Foundation\Http\Response;
use TotalFormVendors\TotalSuite\Foundation\Http\ResponseFactory;

trait WithJsonResponse
{
    /**
     * Convert to HTTP Response.
     *
     * @return Response
     */
    public function toJsonResponse(): Response
    {
        return ResponseFactory::json($this->jsonSerialize());
    }
}