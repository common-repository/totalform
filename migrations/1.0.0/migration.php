<?php
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\League\Container\Container;
use TotalFormVendors\TotalSuite\Foundation\Database\Connection;
use TotalFormVendors\TotalSuite\Foundation\Filesystem;
use TotalFormVendors\TotalSuite\Foundation\Migration\Migration;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Database\WPConnection;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Options;

/**
 * @class Anonymous migration class
 *
 * @param  Container  $container
 * @param           $path
 * @param           $previousVersion
 *
 * @return Migration
 */
return function (Container $container, $path, $previousVersion) {
    return new class($container, $path, $previousVersion) extends Migration {

        protected $version = '1.0.0';

        /**
         * @var Filesystem
         */
        protected $fs;

        /**
         * @var WPConnection
         */
        protected $db;

        /**
         * @var Options
         */
        protected $options;

        /**
         *  constructor.
         *
         * @param  Container  $container
         * @param           $path
         * @param           $previousVersion
         */
        public function __construct(Container $container, $path, $previousVersion)
        {
            parent::__construct($container, $path, $previousVersion);
            $this->db = $this->container->get(Connection::class);
        }


        public function applySchema()
        {
            $schema = $this->fs->glob('/schema/*.sql');

            foreach ($schema as $file) {
                $sql = str_replace('{{prefix}}', $this->db->getTablePrefix(), file_get_contents($file));
                $this->db->raw(trim($sql));
            }
        }
    };
};
