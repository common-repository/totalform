<?php


namespace TotalForm\Actions\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Capabilities\UserCanExportEntries;
use TotalForm\Tasks\Entries\ExportEntries;

class Export extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return UserCanExportEntries::check();
    }

    public function execute()
    {
        return ExportEntries::invoke($this->request->getQueryParams())->toJsonResponse();
    }
}
