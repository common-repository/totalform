<?php

namespace TotalFormVendors\League\Container\Exception;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\Psr\Container\NotFoundExceptionInterface;
use InvalidArgumentException;

class NotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
}
