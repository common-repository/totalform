<?php


namespace TotalForm\Actions\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Tasks\Entries\UpdateEntry;

class Update extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }

    public function execute($entryUid)
    {
        return UpdateEntry::invoke($entryUid, $this->request->getParsedBody())->toJsonResponse();
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'entryUid' => [
                'expression'        => '(?<entryUid>([\w-]+))',
                'sanitize_callback' => static function ($entryUid) {
                    return (string) $entryUid;
                },
            ],
        ];
    }
}
