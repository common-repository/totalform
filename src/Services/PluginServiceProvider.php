<?php

namespace TotalForm\Services;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Validations\InArray;
use TotalForm\Validations\MaxDate;
use TotalForm\Validations\MaxLength;
use TotalForm\Validations\MinDate;
use TotalForm\Validations\MinLength;
use TotalFormVendors\League\Container\Container;
use TotalFormVendors\League\Container\ServiceProvider\AbstractServiceProvider;
use TotalFormVendors\Rakit\Validation\Validator;

/**
 * Class ServiceProvider
 *
 * @package TotalForm
 */
class PluginServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        BlockRegistry::class,
        FormValidator::class,
    ];

    /**
     * @inheritDoc
     */
    public function register()
    {
        /**
         * @var Container $container
         */
        $container = $this->getContainer();

        $container->share(BlockRegistry::class);

        $validator = $container->get(Validator::class);
        $validator->addValidator('in_array', new InArray());
        $validator->addValidator('minLength', new MinLength());
        $validator->addValidator('maxLength', new MaxLength());
        $validator->addValidator('maxDate', new MaxDate());
        $validator->addValidator('minDate', new MinDate());


        $container->share(FormValidator::class)
                  ->addArgument($container->get(Validator::class))
                  ->addArgument($container->get(BlockRegistry::class));
    }
}
