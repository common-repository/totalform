<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Options;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Support\Collection;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Options;

/**
 * Class DefaultOptions
 *
 * @package TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Options
 * @method static Collection invoke(Options $options)
 * @method static Collection invokeWithFallback($fallback, Options $options)
 */
class DefaultOptions extends Task
{
    /**
     * @var Options
     */
    protected $options;

    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    protected function validate()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function execute()
    {
        return $this->options->getDefaults();
    }
}
