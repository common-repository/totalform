<?php

namespace TotalFormVendors\TotalSuite\Foundation;
! defined( 'ABSPATH' ) && exit();


use Composer\Autoload\ClassLoader;
use TotalFormVendors\League\Container\Container;
use TotalFormVendors\League\Container\ServiceProvider\AbstractServiceProvider;
use TotalFormVendors\League\Container\ServiceProvider\BootableServiceProviderInterface;
use TotalFormVendors\League\Flysystem\Adapter\AbstractAdapter;
use TotalFormVendors\Rakit\Validation\Validator;
use TotalFormVendors\TotalSuite\Foundation\Contracts\CallableResolver;
use TotalFormVendors\TotalSuite\Foundation\Contracts\ExceptionHandler as ExceptionHandlerContract;
use TotalFormVendors\TotalSuite\Foundation\Contracts\TrackingStorage;
use TotalFormVendors\TotalSuite\Foundation\CronJobs\CheckLicense;
use TotalFormVendors\TotalSuite\Foundation\CronJobs\TrackEnvironment;
use TotalFormVendors\TotalSuite\Foundation\CronJobs\TrackEvents;
use TotalFormVendors\TotalSuite\Foundation\Database\Connection;
use TotalFormVendors\TotalSuite\Foundation\Database\Query;
use TotalFormVendors\TotalSuite\Foundation\Filesystem\WordPressAdapter;
use TotalFormVendors\TotalSuite\Foundation\Http\CookieJar;
use TotalFormVendors\TotalSuite\Foundation\Http\Request;
use TotalFormVendors\TotalSuite\Foundation\Http\ServerContext;
use TotalFormVendors\TotalSuite\Foundation\Migration\Migrator;
use TotalFormVendors\TotalSuite\Foundation\Validators\DateFormatRule;
use TotalFormVendors\TotalSuite\Foundation\Validators\StringRule;
use TotalFormVendors\TotalSuite\Foundation\View\Engine;
use TotalFormVendors\TotalSuite\Foundation\WordPress\ActionBus;
use TotalFormVendors\TotalSuite\Foundation\WordPress\ActionEmitter;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Admin\Page;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Admin\UninstallFeedback;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Database\WPConnection;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Modules\Manager;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Options;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Plugin;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Rest\ActionResolver;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Rest\Router;
use TotalFormVendors\TotalSuite\Foundation\WordPress\Scheduler;

/**
 * Class CoreProvider
 *
 * @package TotalSuite\Foundation
 */
class CoreProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /**
     * @var array
     */
    protected $provides = [
        ClassLoader::class,
        ExceptionHandlerContract::class,
        Connection::class,
        Filesystem::class,
        Options::class,
        Manager::class,
        Request::class,
        Emitter::class,
        CallableResolver::class,
        Router::class,
        Engine::class,
        Migrator::class,
        Validator::class,
        Scheduler::class,
        TrackingStorage::class,
        CookieJar::class,
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

        /**
         * @var Environment $env
         */
        $env = $container->get(Environment::class);

        // Class loader
        $container->share(
            ClassLoader::class,
            static function () use ($env) {
                return $env['loader'];
            }
        );

        // Exception
        $container->share(
            ExceptionHandlerContract::class,
            static function () use ($env) {
                return new ExceptionHandler($env);
            }
        );

        // Filesystem
        $container->share(
            Filesystem::class,
            static function () use ($env) {
                $adapter = new WordPressAdapter($env->get('path.base'));
                $filesystem = new Filesystem($adapter, ['visibility' => AbstractAdapter::VISIBILITY_PUBLIC]);

                return $filesystem;
            }
        );

        // Plugins Options
        $container->share(Options::class)
                  ->addArgument($env['stores.optionsKey'])
                  ->addArgument($env->get('defaults.options', []));

        // Plugins License
        $container->share(License::class, function () {
            return new License('totalsuite_license', License::getDefault());
        });

        // Tracking Options
        $container->share(TrackingStorage::class, function () {
            return Options::instance()->withKey(Plugin::env('stores.trackingKey'), [
                'screens' => [],
                'features' => []
            ]);
        });

        // Module manager
        $container->share(Manager::class, Manager::class)
                  ->addArgument($container)
                  ->addArgument($env)
                  ->addArgument($container->get(Options::class)->withKey($env['stores.modulesKey']))
                  ->addArgument($container->get(ClassLoader::class));

        // Server Request
        $container->share(
            Request::class,
            static function () {
                return Request::createFromServer(ServerContext::create($_SERVER));
            }
        );

        // Cookie jar
        $container->share(CookieJar::class, static function () {
            return CookieJar::createFromServer();
        });

        // Event Emitter
        $container->share(
            Emitter::class,
            static function () {
                $emitter = new ActionEmitter();
                $emitter->addListener('*', new ActionBus(), ActionEmitter::P_LOW);
                return $emitter;
            }
        );

        // Route Callable Resolver
        $container->share(
            CallableResolver::class,
            static function () use ($container) {
                return new ActionResolver($container);
            }
        );

        // Router
        $container->share(Router::class)
                  ->addArgument($container->get(CallableResolver::class))
                  ->addArgument($env->get('namespaces.rest', $env->get('product.id')))
                  ->addArgument($container->get(ExceptionHandlerContract::class));

        // Scheduler
        $container->share(Scheduler::class, function () {
            $productId = Plugin::env('product.id');

            $scheduler = new Scheduler();
            $scheduler->addCronJob("{$productId}_weekly_environment", new TrackEnvironment());
            $scheduler->addCronJob("{$productId}_daily_activity", new TrackEvents());
            $scheduler->addCronJob('totalsuite_check_license', new CheckLicense());

            return $scheduler;
        });

        // Validator
        $container->share(
            Validator::class,
            static function () {
                $validator = new Validator();
                $validator->addValidator('string', new StringRule());
                $validator->addValidator('dateFormat', new DateFormatRule());

                return $validator;
            }
        );

        // Migrator
        $container->share(Migrator::class)->addArgument($container);

        // Uninstall feedback
        $container->share(UninstallFeedback::class, new UninstallFeedback());
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        /**
         * @var Container $container
         */
        $container = $this->getContainer();

        /**
         * @var Environment $env
         */
        $env = $container->get(Environment::class);

        // Database
        $container->share(
            Connection::class,
            static function () use ($env) {
                return new WPConnection(DB_NAME, $env->get('db.prefix', 'wp_'));
            }
        );

        // Template Engine
        $container->share(
            Engine::class,
            static function () use ($env) {
                $engine = new Engine($env->get('path.base') . 'views');
                $engine->addFolder('marketing', dirname(__DIR__) . '/views/marketing');

                return $engine;
            }
        );

        // Initialize Model
        Query::setConnection($container->get(Connection::class));

        // Initialize View Engine
        Page::setEngine($container->get(Engine::class));
    }
}
