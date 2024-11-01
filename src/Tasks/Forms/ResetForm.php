<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Entry;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\ValidationException;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class ResetForm
 *
 * @package TotalForm\Tasks\Forms
 * @method static boolean invoke(string $formUid)
 */
class ResetForm extends Task
{
    /**
     * @var $formUid
     */
    protected $formUid;

    /**
     * ResetForm constructor.
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
        return Entry::query()->delete()->where('form_uid', $this->formUid)->execute();
    }
}
