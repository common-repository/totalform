<?php


namespace TotalForm\Tasks\Forms;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Models\Form;
use TotalFormVendors\TotalSuite\Foundation\Database\Query;
use TotalFormVendors\TotalSuite\Foundation\Database\Query\Select;
use TotalFormVendors\TotalSuite\Foundation\Support\Arrays;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class GetForms
 *
 * @package TotalForm\Tasks\Forms
 * @method static Query\Pagination invoke(array $filters)
 */
class GetForms extends Task
{
    const PER_PAGE = 20;

    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = Collection::create(
            Arrays::merge(
                [
                    'from_date' => null,
                    'to_date'   => null,
                    'page'      => 1,
                    'per_page'  => static::PER_PAGE,
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
        $query = Form::query()->select();
        $query->orderBy('id', 'desc');


        if ($this->filters['from_date']) {
            $query->whereDate('updated_at', '>=', $this->filters['from_date']);
        }

        if ($this->filters['to_date']) {
            $query->whereDate('updated_at', '<=', $this->filters['to_date']);
        }

        $this->filters['per_page'] = ($this->filters['per_page'] < 1) ? static::PER_PAGE : $this->filters['per_page'];

        return $query->paginate($this->filters['per_page'], $this->filters['page'] ?? 1);
    }
}
