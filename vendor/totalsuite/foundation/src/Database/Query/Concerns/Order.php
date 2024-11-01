<?php

namespace TotalFormVendors\TotalSuite\Foundation\Database\Query\Concerns;
! defined( 'ABSPATH' ) && exit();


/**
 * Trait Order
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Database\Query\Concerns
 */
trait Order
{
    /**
     * @var array
     */
    protected $orders = [];

    /**
     * @param $column
     * @param $direction
     *
     * @return static
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->orders[] = [$column, strtoupper($direction)];
        return $this;
    }
}