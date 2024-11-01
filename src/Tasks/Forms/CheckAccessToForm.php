<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();



use TotalForm\Capabilities\UserCanViewForms;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Task;

class CheckAccessToForm extends Task
{
    protected $form;

    /**
     * CheckAccess constructor.
     *
     * @param $form
     */
    public function __construct($form)
    {
        $this->form = $form;
    }


    protected function validate()
    {
        return true;
    }

    public function getAllowMessage()
    {
        $message = 'You are not allowed to access this form.';
        if (!empty($this->form->settings->texts[$message])) {
            return __(
                    $this->form->settings->texts[$message],
                    'totalform'
                ) ?? '';
        }

        return __($message, 'totalform');
    }

    /**
     * @return mixed|void
     * @throws Exception
     */
    protected function execute()
    {
        Exception::throwIf(
            $this->form && $this->form->published_at === null && !UserCanViewForms::check(),
            $this->getAllowMessage()
        );
    }
}
