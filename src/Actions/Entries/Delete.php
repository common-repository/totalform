<?php


namespace TotalForm\Actions\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Capabilities\UserCanDeleteEntries;
use TotalForm\Tasks\Entries\DeleteEntries;

class Delete extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return UserCanDeleteEntries::check();
    }


    public function execute()
    {
        return DeleteEntries::invoke($this->request->getParsedBodyParam('entries'))
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
