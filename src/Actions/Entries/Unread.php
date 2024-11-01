<?php


namespace TotalForm\Actions\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Tasks\Entries\UnreadEntries;

class Unread extends \TotalFormVendors\TotalSuite\Foundation\Action
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
        return UnreadEntries::invoke($this->request->getParsedBodyParam('entries'))->toJsonResponse();
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [];
    }
}
