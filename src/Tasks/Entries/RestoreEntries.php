<?php


namespace TotalForm\Tasks\Entries;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Entry;
use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class RestoreEntries
 *
 * @package TotalForm\Tasks\Entries
 * @method static Form invoke(array $entriesUids)
 */
class RestoreEntries extends Task
{
    protected $entriesUids;

    public function __construct($entriesUids = [])
    {
        $this->entriesUids = Collection::create($entriesUids);
    }

    /**
     * @inheritDoc
     */
    protected function validate()
    {
    }

    /**
     * @inheritDoc
     * @throws \TotalFormVendors\TotalSuite\Foundation\Exceptions\DatabaseException
     */
    protected function execute()
    {
        Entry::query()
             ->update(['deleted_at' => null])
             ->whereIn('uid', $this->entriesUids->toArray())
             ->execute();

        return $this->entriesUids;
    }
}
