<?php


namespace TotalForm\Actions\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Capabilities\UserCanDeleteEntries;
use TotalForm\Tasks\Entries\RestoreEntries;

class Restore extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return UserCanDeleteEntries::check();
    }

    public function execute()
    {
        return RestoreEntries::invoke($this->request->getParsedBodyParam('entries'))
                             ->toJsonResponse();
    }


    /**
     * @return array
     */
    public function getParams(): array
    {
        return [];
    }
}
