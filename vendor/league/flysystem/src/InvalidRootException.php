<?php

namespace TotalFormVendors\League\Flysystem;
! defined( 'ABSPATH' ) && exit();


use RuntimeException;

class InvalidRootException extends RuntimeException implements FilesystemException
{
}
