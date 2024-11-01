<?php


namespace TotalForm\Tasks\Entries;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Exceptions\Entries\EntryNotFound;
use TotalForm\Models\Entry;
use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\ValidationException;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class GetEntry
 *
 * @package TotalForm\Tasks\Entries
 * @method static Form invoke(string $entryUid)
 */
class GetEntry extends Task
{
    /**
     * @var $entryUid
     */
    protected $entryUid;

    /**
     * GetEntry constructor.
     *
     * @param $entryUid
     */
    public function __construct($entryUid)
    {
        $this->entryUid = $entryUid;
    }

    /**
     * @inheritDoc
     */
    protected function validate()
    {
        ValidationException::throwUnless(is_string($this->entryUid));
    }

    /**
     * @inheritDoc
     */
    protected function execute()
    {
        $form = Entry::byUid($this->entryUid);
        EntryNotFound::throwIf($form->deleted_at, __('Entry is trashed.', 'totalform'));

        return $form;
    }
}
