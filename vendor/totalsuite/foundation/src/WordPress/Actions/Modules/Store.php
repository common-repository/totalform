<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Actions\Modules;
! defined( 'ABSPATH' ) && exit();


use Exception;
use TotalFormVendors\TotalSuite\Foundation\Action;
use TotalFormVendors\TotalSuite\Foundation\Http\Response;
use TotalFormVendors\TotalSuite\Foundation\Support\Arrays;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Manager;

class Store extends Action
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
     * @return Response
     * @throws Exception
     */
    public function execute()
    {
        $data = Arrays::only((array)$this->request->getParsedBody(), ['usage', 'audience', 'tracking', 'newsletter']);
        return $this->manager->fetchStore($data)->values()->toJsonResponse();
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
