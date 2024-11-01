<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\ValidationException;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class TrashForm
 *
 * @package TotalForm\Tasks\Forms
 * @method static Form invoke(string $formUid)
 */
class TrashForm extends Task
{
    /**
     * @var $formUid
     */
    protected $formUid;

    /**
     * TrashForm constructor.
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
        $form             = Form::byUid($this->formUid);
        $form->deleted_at = date_i18n('Y-m-d H:i:s');
        Exception::throwUnless(
            $form->save(),
            __('Something went wrong during the process.', 'totalform')
        );

        return $form;
    }
}
