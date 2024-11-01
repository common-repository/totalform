<?php


namespace TotalForm\Actions\Forms;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Capabilities\UserCanViewForms;
use TotalForm\Models\Form;
use TotalForm\Tasks\Forms\GetForms;

class Index extends \TotalFormVendors\TotalSuite\Foundation\Action
{
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return UserCanViewForms::check();
    }


    public function execute()
    {
        return GetForms::invoke($this->request->getQueryParams())
                       ->map(static function (Form $form) {
                           return $form->withEntriesCount()->withEntriesUnreadCount();
                       })
                       ->toJsonResponse();
    }
}
