<?php

namespace TotalFormVendors\League\Flysystem;
! defined( 'ABSPATH' ) && exit();


use LogicException;

class RootViolationException extends LogicException implements FilesystemException
{
    //
}