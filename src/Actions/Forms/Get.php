<?php


namespace TotalForm\Actions\Forms;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Capabilities\UserCanViewForms;
use TotalForm\Tasks\Forms\GetForm;

class Get extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return UserCanViewForms::check();
    }


    public function execute($formUid)
    {
        return GetForm::invoke($formUid)->withEntriesCount()->withEntriesUnreadCount()->toJsonResponse();
    }


    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'formUid' => [
                'expression'        => '(?<formUid>([\w-]+))',
                'sanitize_callback' => static function ($formUid) {
                    return (string) $formUid;
                },
            ],
        ];
    }
}
