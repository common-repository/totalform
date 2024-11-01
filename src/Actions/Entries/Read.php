<?php


namespace TotalForm\Actions\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Tasks\Entries\ReadEntries;

class Read extends \TotalFormVendors\TotalSuite\Foundation\Action
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
        return ReadEntries::invoke($this->request->getParsedBodyParam('entries'))->toJsonResponse();
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [];
    }
}
