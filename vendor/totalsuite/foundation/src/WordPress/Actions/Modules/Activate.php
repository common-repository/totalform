<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Modules;
! defined( 'ABSPATH' ) && exit();


use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Http\Response;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Manager;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Tasks\Modules\ActivateModule;

class Activate extends Action
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * Index constructor.
     *
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param $moduleId
     *
     * @return Response
     * @throws Exception
     */
    public function execute($moduleId): Response
    {
        return ActivateModule::invoke($this->manager, $moduleId)
                             ->toJsonResponse();
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'moduleId' => [
                'expression' => '(?<moduleId>([\w-]+))',
                'sanitize_callback' => static function ($moduleId) {
                    return (string)$moduleId;
                },
            ],
        ];
    }
}
