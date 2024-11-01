<?php


namespace TotalForm\Actions\Forms;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Capabilities\UserCanDeleteForm;
use TotalForm\Tasks\Forms\RestoreForm;

class Restore extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return UserCanDeleteForm::check();
    }


    public function execute($formUid)
    {
        return RestoreForm::invoke($formUid)->toJsonResponse();
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
