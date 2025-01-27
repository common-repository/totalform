<?php

namespace TotalFormVendors\TotalSuite\Foundation\Migration\Concerns;
! defined( 'ABSPATH' ) && exit();


use Migrate;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\MigrationException;
use TotalFormVendors\TotalSuite\Foundation\Migration\Migration;

trait MigrationQueue
{
    /**
     * @var Migration[]
     */
    protected $migrations = [];


    protected function fetchMigrations()
    {
        $migrations = glob($this->env->get('path.migrations') . '/**/migration.php');

        foreach ($migrations as $migration) {
            $version = basename(dirname($migration));

            if (version_compare($version, $this->currentVersion, '>')) {
                $this->migrations[$version] = $migration;
            }
        }
    }

    /**
     * @param string $path
     * @param string $previousVersion
     *
     * @return Migration
     * @throws MigrationException
     */
    protected function loadMigration($path, $previousVersion): Migration
    {
        if (!file_exists($path)) {
            throw new MigrationException('Can not load migrate class');
        }

        $migration = require $path;

        return $migration($this->container, dirname($path), $previousVersion);
    }
}