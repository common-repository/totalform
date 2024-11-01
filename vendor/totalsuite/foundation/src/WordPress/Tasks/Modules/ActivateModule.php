<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Modules;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Events\Modules\OnActivateModule;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\ModuleException;
use TotalFormVendors\TotalSuite\Foundation\Task;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Definition;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Manager;

/**
 * Class ActivateModule
 *
 * @method static Definition invoke(Manager $manager, string $moduleId)
 * @method static Definition invokeWithFallback($fallback, Manager $manager, string $moduleId)
 */
class ActivateModule extends Task
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $moduleId;

    /**
     * Activate constructor.
     *
     * @param Manager $manager
     * @param string $moduleId
     */
    public function __construct(Manager $manager, string $moduleId)
    {
        $this->manager = $manager;
        $this->moduleId = $moduleId;
    }


    /**
     * @inheritDoc
     */
    protected function validate()
    {
        return $this->manager->getDefinition($this->moduleId) !== null;
    }

    /**
     * @inheritDoc
     * @throws ModuleException
     * @throws Exception
     */
    protected function execute()
    {
        Exception::throwUnless($this->manager->activate($this->moduleId), 'Cannot activate module');

        $definition = $this->manager->getDefinition($this->moduleId);

        OnActivateModule::emit($definition);

        return $definition;
    }
}
