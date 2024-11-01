<?php

namespace TotalFormVendors\TotalSuite\Foundation\Database\Concerns;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Query;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\DatabaseException;

trait QueryHelpers
{
    /**
     * @param $name
     * @param null $model
     * @return Query
     * @throws DatabaseException
     */
    public static function table($name, $model = null)
    {
        $query = new Query($name);
        if ($model) {
            $query->setModel($model);
        }

        return $query;
    }
}
