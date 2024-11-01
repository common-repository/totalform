<?php


namespace TotalForm\Tasks\Entries;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Entry;
use TotalFormVendors\TotalSuite\Foundation\Database\Query;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class BookmarkEntries
 *
 * @package TotalForm\Tasks\Entries
 * @method static Query\Pagination invoke(array $entriesUids)
 */
class RemoveBookmarkEntries extends Task
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
     */
    protected function execute()
    {
        Entry::query()
             ->update(['bookmarked_at' => null])
             ->whereIn('uid', $this->entriesUids->toArray())
             ->execute();

        return $this->entriesUids;
    }
}
