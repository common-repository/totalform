<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\ValidationException;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class DeleteForm
 *
 * @package TotalForm\Tasks\Forms
 * @method static Form invoke(string $formUid)
 */
class DeleteForm extends Task
{
    /**
     * @var $formUid
     */
    protected $formUid;

    /**
     * DeleteForm constructor.
     *
     * @param $formUid
     */
    public function __construct($formUid)
    {
        $this->formUid = $formUid;
    }

    /**
     * @inheritDoc
     */
    protected function validate()
    {
        ValidationException::throwUnless(is_string($this->formUid));
    }

    /**
     * @inheritDoc
     */
    protected function execute()
    {
        $form = Form::byUid($this->formUid);
        Exception::throwUnless(
            $form->delete(),
            __('Something went wrong during the process.', 'totalform')
        );

        return $form;
    }
}
