<?php

namespace TotalForm\Models;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Database\Model;
use TotalFormVendors\TotalSuite\Foundation\Support\Collection;

/**
 * Class FormNotification
 *
 * @property boolean $enabled
 * @property boolean $to
 * @property boolean $replyTo
 * @property boolean $subject
 * @property boolean $body
 * @property Collection $arguments
 *
 * @package TotalForm\Models
 */
class FormNotification extends Model
{
    /**
     * @var array
     */
    protected $types = [
        'enabled'   => 'boolean',
        'to'        => 'string',
        'replyTo'   => 'string',
        'subject'   => 'string',
        'body'      => 'string',
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
