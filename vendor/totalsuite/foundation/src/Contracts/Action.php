<?php

namespace TotalFormVendors\TotalSuite\Foundation\Contracts;
! defined( 'ABSPATH' ) && exit();


/**
 * Interface Action
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Contracts
 */
interface Action
{
    /**
     * @return bool
     */
    public function authorize(): bool;

    /**
     * @return array
     */
    public function getParams(): array;
}