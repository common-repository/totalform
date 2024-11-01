<?php declare(strict_types=1);

namespace TotalFormVendors\League\Container\Argument;
! defined( 'ABSPATH' ) && exit();


interface ClassNameInterface
{
    /**
     * Return the class name.
     *
     * @return string
     */
    public function getClassName() : string;
}
