<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Modules;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Http\Response;
use TotalFormVendors\TotalSuite\Foundation\Support\Arrays;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Manager;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Modules\InstallModule;

class Install extends Action
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * Install constructor.
     *
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function execute(): Response
    {
        $uploaded = Arrays::get($this->request->getUploadedFiles(), 'module', null);
        Exception::throwUnless($uploaded, 'No file was uploaded');

        return InstallModule::invoke($this->manager, $uploaded)
                            ->toJsonResponse();
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
