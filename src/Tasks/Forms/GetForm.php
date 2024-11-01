<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Exceptions\Forms\FormNotFound;
use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\ValidationException;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class GetForm
 *
 * @package TotalForm\Tasks\Forms
 * @method static Form invoke(string $formUid)
 */
class GetForm extends Task
{
    /**
     * @var $formUid
     */
    protected $formUid;

    /**
     * GetForm constructor.
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
        FormNotFound::throwIf($form->deleted_at, __('Form is trashed.', 'totalform'));

        return $form;
    }
}
