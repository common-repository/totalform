<?php


namespace TotalForm\Actions\Forms;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Capabilities\UserCanCreateForm;
use TotalForm\Tasks\Forms\CreateForm;

class Create extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return UserCanCreateForm::check();
    }


    public function execute()
    {
        $data = $this->request->getParsedBody();

        return CreateForm::invoke($data)->toJsonResponse();
    }
}
