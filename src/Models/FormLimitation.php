<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Model;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class FormLimitation
 *
 * @property boolean $enabled
 * @property Collection $arguments
 *
 * @package TotalForm\Models
 */
class FormLimitation extends Model
{
    /**
     * @var array
     */
    protected $types = [
        'enabled'   => 'boolean',
        'arguments' => 'arguments',
    ];

    public function castToArguments($data)
    {
        return Collection::create($data);
    }

    public function jsonSerialize(): array
    {
        $json              = parent::jsonSerialize();
        $json['arguments'] = (object) $json['arguments']->toArray();

        return $json;
    }
}
