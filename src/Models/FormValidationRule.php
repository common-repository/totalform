<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Model;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class FormValidationRule
 *
 * @property string $name
 * @property Collection $arguments
 *
 * @package TotalForm\Models
 */
class FormValidationRule extends Model
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

    public function jsonSerialize(): array
    {
        $json              = parent::jsonSerialize();
        $json['arguments'] = (object) $json['arguments']->toArray();

        return $json;
    }
}
