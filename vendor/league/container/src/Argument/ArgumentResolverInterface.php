<?php declare(strict_types=1);

namespace TotalFormVendors\League\Container\Argument;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\League\Container\ContainerAwareInterface;
use ReflectionFunctionAbstract;

interface ArgumentResolverInterface extends ContainerAwareInterface
{
    /**
     * Resolve an array of arguments to their concrete implementations.
     *
     * @param array $arguments
     *
     * @return array
     */
    public function resolveArguments(array $arguments) : array;

    /**
     * Resolves the correct arguments to be passed to a method.
     *
     * @param ReflectionFunctionAbstract $method
     * @param array                      $args
     *
     * @return array
     */
    public function reflectArguments(ReflectionFunctionAbstract $method, array $args = []) : array;
}
