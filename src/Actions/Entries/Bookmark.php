<?php


namespace TotalForm\Actions\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Tasks\Entries\BookmarkEntries;

class Bookmark extends \TotalFormVendors\TotalSuite\Foundation\Action
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
        return BookmarkEntries::invoke($this->request->getParsedBodyParam('entries'))->toJsonResponse();
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [];
    }
}
