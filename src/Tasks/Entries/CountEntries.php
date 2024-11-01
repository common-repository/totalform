<?php


namespace TotalForm\Tasks\Entries;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Entry;
use TotalFormVendors\TotalSuite\Foundation\Database\Query;
use TotalFormVendors\TotalSuite\Foundation\Database\Query\Select;
use TotalFormVendors\TotalSuite\Foundation\Support\Arrays;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class CountEntries
 *
 * @package TotalForm\Tasks\Entries
 * @method static Query\Pagination invoke(array $filters)
 */
class CountEntries extends Task
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = Collection::create(
            Arrays::merge(
                [
                    'form_uid'  => null,
                    'from_date' => null,
                    'to_date'   => null,
                    'status'    => null,
                    'search'    => null,
                ],
                (array) $filters
            )
        );
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
        /**
         * @var Query|Select $query
         */
        $query = Entry::query()->select();
        $query->orderBy('id', 'desc');

        //@TODO: Review this, probably exposing all entries is not a good idea (in case form_uid is empty)
        if ($this->filters['form_uid']) {
            $query->where('form_uid', $this->filters['form_uid']);
        }

        if ($this->filters['from_date']) {
            $query->whereDate('created_at', '>=', $this->filters['from_date']);
        }

        if ($this->filters['to_date']) {
            $query->whereDate('created_at', '<=', $this->filters['to_date']);
        }

        if ($this->filters['status'] === 'read') {
            $query->whereNotNull('read_at');
        }

        if ($this->filters['status'] === 'unread') {
            $query->whereNull('read_at');
        }

        if ($this->filters['status'] === 'bookmarked') {
            $query->whereNotNull('bookmarked_at');
        }

        if ($this->filters['status'] === 'trashed') {
            $query->whereNotNull('deleted_at');
        } else {
            $query->whereNull('deleted_at');
        }

        return $query->count();
    }
}
