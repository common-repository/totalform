<?php

namespace TotalFormVendors\TotalSuite\Foundation\Database\Query;
! defined( 'ABSPATH' ) && exit();



use TotalFormVendors\TotalSuite\Foundation\Database\Query\Concerns\Limit;
use TotalFormVendors\TotalSuite\Foundation\Database\Query\Concerns\Order;
use TotalFormVendors\TotalSuite\Foundation\Database\Query\Concerns\Where;

/**
 * Class Delete
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Database\Query
 */
class Delete extends BaseQuery
{
    use Where, Order, Limit;
}