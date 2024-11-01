<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Model;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class FormCondition
 *
 * @property string $field_uid
 * @property string $operator
 * @property mixed $value
 *
 * @package TotalForm\Models
 */
class FormCondition extends Model
{
    /**
     * @var array
     */
    protected $types = [
        'arguments' => 'arguments',
    ];

    public function castToArguments($data)
    {
        return Collection::create($data);
    }
}
