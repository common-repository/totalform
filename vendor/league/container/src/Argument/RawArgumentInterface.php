<?php declare(strict_types=1);

namespace TotalFormVendors\League\Container\Argument;
! defined( 'ABSPATH' ) && exit();


interface RawArgumentInterface
{
    /**
     * Return the value of the raw argument.
     *
     * @return mixed
     */
    public function getValue();
}
