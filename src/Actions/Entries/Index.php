<?php


namespace TotalForm\Actions\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Tasks\Entries\GetEntries;

class Index extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }

    public function execute()
    {

        return GetEntries::invoke($this->request->getQueryParams())->toJsonResponse();
    }
}
