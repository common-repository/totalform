<?php


namespace TotalForm\Tasks\Entries;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Entry;
use TotalFormVendors\TotalSuite\Foundation\Database\Query;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class ReadEntries
 *
 * @package TotalForm\Tasks\Entries
 * @method static Query\Pagination invoke(array $entriesUids)
 */
class ReadEntries extends Task
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
             ->update(['read_at' => date_i18n('Y-m-d H:i:s')])
             ->whereIn('uid', $this->entriesUids->toArray())
             ->execute();

        return $this->entriesUids;
    }
}
