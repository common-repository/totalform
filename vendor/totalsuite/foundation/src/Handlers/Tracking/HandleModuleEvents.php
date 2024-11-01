<?php
namespace TotalFormVendors\TotalSuite\Foundation\Handlers\Tracking;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\League\Event\EventInterface;
use TotalFormVendors\TotalSuite\Foundation\Contracts\TrackingStorage;
use TotalFormVendors\TotalSuite\Foundation\Events\Modules\OnActivateModule;
use TotalFormVendors\TotalSuite\Foundation\Events\Modules\OnDeactivateModule;
use TotalFormVendors\TotalSuite\Foundation\Events\Modules\OnInstallModule;
use TotalFormVendors\TotalSuite\Foundation\Events\Modules\OnUninstallModule;
use TotalFormVendors\TotalSuite\Foundation\Listener;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Plugin;

class HandleModuleEvents extends Listener
{
    /**
     * @var Options
     */
    protected $options;

    /**
     * HandleModuleEvents constructor.
     *
     */
    public function __construct()
    {
        $this->options = Plugin::get(TrackingStorage::class);
    }


    /**
     * @param  EventInterface|OnActivateModule|OnDeactivateModule|OnInstallModule|OnUninstallModule  $event
     */
    public function handle(EventInterface $event)
    {
        $action = [
            'label'  => $event->definition->get('id'),
            'date'   => date(DATE_ATOM),
        ];

        switch (get_class($event)) {
            case OnActivateModule::class :
            {
                $action['action'] = 'activate';
                break;
            }
            case OnDeactivateModule::class :
            {
                $action['action'] = 'deactivate';
                break;
            }
            case OnInstallModule::class :
            {
                $action['action'] = 'install';
                break;
            }
            case OnUninstallModule::class :
            {
                $action['action'] = 'uninstall';
                break;
            }
        }

        $storage = $this->options->get('features', []);
        $storage[] = $action;
        $this->options->set('features', $storage)->save();
    }
}