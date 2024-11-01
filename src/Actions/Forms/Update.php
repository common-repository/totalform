<?php


namespace TotalForm\Actions\Forms;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Capabilities\UserCanUpdateForm;
use TotalForm\Tasks\Forms\UpdateForm;

class Update extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return UserCanUpdateForm::check();
    }

    public function execute($formUid)
    {
        return UpdateForm::invoke($formUid, $this->request->getParsedBody())->toJsonResponse();
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
