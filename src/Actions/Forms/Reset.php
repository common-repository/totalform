<?php


namespace TotalForm\Actions\Forms;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Capabilities\UserCanDeleteEntries;
use TotalForm\Tasks\Forms\ResetForm;

class Reset extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return UserCanDeleteEntries::check();
    }


    public function execute($formUid)
    {
        return ResetForm::invoke($formUid);
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
