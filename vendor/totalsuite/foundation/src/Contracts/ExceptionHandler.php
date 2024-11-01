<?php

namespace TotalFormVendors\TotalSuite\Foundation\Contracts;
! defined( 'ABSPATH' ) && exit();



use Throwable;

/**
 * Interface ExceptionHandlerInterface
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Contracts
 */
interface ExceptionHandler
{
    /**
     * @param Throwable $e
     *
     * @return mixed
     */
    public function handle(Throwable $e);
}