<?php

namespace TotalFormVendors\TotalSuite\Foundation\WordPress\Modules;
! defined( 'ABSPATH' ) && exit();



use TotalFormVendors\League\Container\Container;
use TotalFormVendors\TotalSuite\Foundation\View\Engine;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Module;

/**
 * Class Template
 *
 * @package TotalFormVendors\TotalSuite\Foundation\WordPress\Module
 */
abstract class Template extends Module
{

    /**
     * @var Engine
     */
    protected $engine;

    /**
     * Template constructor.
     *
     * @param Definition $definition
     * @param Container $container
     */
    public function __construct(Definition $definition, Container $container)
    {
        parent::__construct($definition, $container);

        $this->engine = $container->get(Engine::class, true);
        $this->engine->setDirectory($this->getPath() . DIRECTORY_SEPARATOR . 'views');
    }

    /**
     * @param string $template
     * @param array $data
     *
     * @return string
     */
    public function view(string $template, array $data = []): string
    {
        return $this->engine->render($template, $data + ['template' => $this]);
    }
}