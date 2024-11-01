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
 * Class ExportEntries
 *
 * @package TotalForm\Tasks\Entries
 * @method static Query\Pagination invoke(array $filters)
 */
class ExportEntries extends Task
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
        //@TODO: To be implemented

    }
}
