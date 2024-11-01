<?php


namespace TotalForm\Actions\Entries;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Capabilities\UserCanViewEntries;
use TotalForm\Tasks\Entries\GetEntry;

class Get extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return UserCanViewEntries::check();
    }


    public function execute($entryUid)
    {
        return GetEntry::invoke($entryUid)->toJsonResponse();
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
