<?php

namespace TotalFormVendors\TotalSuite\Foundation\Contracts\Support;
! defined( 'ABSPATH' ) && exit();


use JsonSerializable;

/**
 * Interface Arrayable
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Contracts\Support
 */
interface Arrayable extends JsonSerializable
{
    /**
     * @return array
     */
    public function toArray(): array;
}