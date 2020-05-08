<?php
/**
 * Author: Joker
 * Date: 2020-05-08 13:57
 */

namespace JokerProject\LaravelAliyunAmqp\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use JokerProject\LaravelAliyunAmqp\Builder\ContainerBuilder;
use JokerProject\LaravelAliyunAmqp\Command\BaseConsumerCommand;
use JokerProject\LaravelAliyunAmqp\Command\BasePublisherCommand;
use JokerProject\LaravelAliyunAmqp\Command\DeleteAllCommand;
use JokerProject\LaravelAliyunAmqp\Command\SetupCommand;
use JokerProject\LaravelAliyunAmqp\Command\ListEntitiesCommand;
use JokerProject\LaravelAliyunAmqp\ConfigHelper;
use JokerProject\LaravelAliyunAmqp\ConsumerInterface;
use JokerProject\LaravelAliyunAmqp\Container;
use JokerProject\LaravelAliyunAmqp\Exception\LaravelRabbitMqException;
use JokerProject\LaravelAliyunAmqp\PublisherInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ServiceProvider
 *
 * @package JokerProject\LaravelAliyunAmqp\Providers
 * @author  Adrian Tilita <adrian@tilita.ro>
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     * @throws LaravelRabbitMqException
     */
    public function boot()
    {
        $this->publishConfig();
        $this->registerContainer();
        $this->registerPublishers();
        $this->registerConsumers();
        $this->registerCommands();
    }

    /**
     * Publish Config
     */
    private function publishConfig()
    {
        $this->publishes([
            realpath(
                dirname(__FILE__)
            ) . '/../../config/laravel_rabbitmq.php' => config_path('laravel_rabbitmq.php'),
        ]);
    }

    /**
     * Create container and register binding
     */
    private function registerContainer()
    {
        $config = config('laravel_rabbitmq', []);
        if (!is_array($config)) {
            throw new \RuntimeException(
                "Invalid configuration provided for LaravelRabbitMQ!"
            );
        }
        $configHelper = new ConfigHelper();
        $config = $configHelper->addDefaults($config);

        $this->app->singleton(
            Container::class,
            function () use ($config) {
                $container = new ContainerBuilder();
                return $container->createContainer($config);
            }
        );
    }

    /**
     * Register publisher bindings
     */
    private function registerPublishers()
    {
        // Get "tagged" like Publisher
        $this->app->singleton(PublisherInterface::class, function (Application $application, $arguments) {
            /** @var Container $container */
            $container = $application->make(Container::class);
            if (empty($arguments)) {
                throw new \RuntimeException("Cannot make Publisher. No publisher identifier provided!");
            }
            $aliasName = $arguments[0];
            return $container->getPublisher($aliasName);
        });
    }

    /**
     * Register consumer bindings
     */
    private function registerConsumers()
    {
        // Get "tagged" like Consumers
        $this->app->singleton(ConsumerInterface::class, function (Application $application, $arguments) {
            /** @var Container $container */
            $container = $application->make(Container::class);
            if (empty($arguments)) {
                throw new \RuntimeException("Cannot make Consumer. No consumer identifier provided!");
            }
            $aliasName = $arguments[0];

            if (!$container->hasConsumer($aliasName)) {
                throw new \RuntimeException("Cannot make Consumer.\nNo consumer with alias name {$aliasName} found!");
            }
            /** @var LoggerAwareInterface $consumer */
            $consumer = $container->getConsumer($aliasName);
            $consumer->setLogger($application->make(LoggerInterface::class));
            return $consumer;
        });
    }

    /**
     * Register commands
     */
    private function registerCommands()
    {
        $this->commands([
            SetupCommand::class,
            ListEntitiesCommand::class,
            BaseConsumerCommand::class,
            DeleteAllCommand::class,
            BasePublisherCommand::class
        ]);
    }
}
