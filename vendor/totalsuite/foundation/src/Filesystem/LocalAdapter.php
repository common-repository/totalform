<?php

namespace TotalFormVendors\TotalSuite\Foundation\Filesystem;
! defined( 'ABSPATH' ) && exit();



use TotalFormVendors\League\Flysystem\Adapter\CanOverwriteFiles;
use TotalFormVendors\League\Flysystem\Adapter\Local;

/**
 * Class LocalAdapter
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Filesystem
 */
class LocalAdapter extends Local implements CanOverwriteFiles
{

}