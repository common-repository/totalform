<?php

namespace TotalFormVendors\TotalSuite\Foundation\Exceptions;
! defined( 'ABSPATH' ) && exit();



/**
 * Class UnauthorizedException
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Exceptions
 */
class UnauthorizedException extends Exception
{
    const CODE = 401;
}