<?php

namespace TotalFormVendors\League\Flysystem\Plugin;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\League\Flysystem\FilesystemInterface;
use TotalFormVendors\League\Flysystem\PluginInterface;

abstract class AbstractPlugin implements PluginInterface
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * Set the Filesystem object.
     *
     * @param FilesystemInterface $filesystem
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }
}
