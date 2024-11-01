<?php

namespace TotalFormVendors\League\Container\Exception;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\Psr\Container\ContainerExceptionInterface;
use RuntimeException;

class ContainerException extends RuntimeException implements ContainerExceptionInterface
{
}
