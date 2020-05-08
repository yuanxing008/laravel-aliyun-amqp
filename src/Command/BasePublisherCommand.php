<?php
/**
 * Author: Joker
 * Date: 2020-05-08 13:57
 */

namespace JokerProject\LaravelAliyunAmqp\Command;

use Illuminate\Console\Command;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use JokerProject\LaravelAliyunAmqp\ConsumerInterface;
use JokerProject\LaravelAliyunAmqp\Container;
use JokerProject\LaravelAliyunAmqp\PublisherInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BasePublisherCommand
 *
 * @package JokerProject\LaravelAliyunAmqp\Command
 */
class BasePublisherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:publish {publisher} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish one message';

    /**
     * @var null|Container
     */
    private $container = null;

    /**
     * BasePublisherCommand constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    /**
     * @param string $publisherAliasName
     * @return PublisherInterface
     */
    protected function getPublisher(string $publisherAliasName): PublisherInterface
    {
        return $this->container->getPublisher($publisherAliasName);
    }

    /**
     * Execute the console command.
     * @return int
     */
    public function handle()
    {
        $this->getPublisher($this->input->getArgument('publisher'))
            ->publish($this->input->getArgument('message'));
    }
}