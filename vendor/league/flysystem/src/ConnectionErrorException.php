<?php

namespace TotalFormVendors\League\Flysystem;
! defined( 'ABSPATH' ) && exit();


use ErrorException;

class ConnectionErrorException extends ErrorException implements FilesystemException
{
}