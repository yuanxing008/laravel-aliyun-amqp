<?php
/**
 * Author: Joker
 * Date: 2020-05-08 13:57
 */

namespace JokerProject\LaravelAliyunAmqp\Command;

use Illuminate\Console\Command;
use JokerProject\LaravelAliyunAmqp\Container;
use JokerProject\LaravelAliyunAmqp\Entity\QueueEntity;
use JokerProject\LaravelAliyunAmqp\PublisherInterface;

/**
 * Class DeleteAllCommand
 *
 * @package JokerProject\LaravelAliyunAmqp\Commad
 */
class DeleteAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:delete-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all queues, exchanges and binds that are defined in entities AND referenced to' .
    ' either a publisher or a consumer';

    /**
     * @var Container
     */
    private $container;

    /**
     * CreateEntitiesCommand constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hasErrors = false;
        /** @var PublisherInterface $entity */
        foreach ($this->container->getPublishers() as $publisherName => $entity) {
            try {
                $entity->delete();
                $this->output->writeln(
                    sprintf(
                        "Deleted entity <info>%s</info> for publisher [<fg=yellow>%s</>]",
                        (string)$entity->getAliasName(),
                        (string)$publisherName
                    )
                );
            } catch (\Exception $e) {
                $hasErrors = true;
                $this->output->error(
                    sprintf(
                        "Could not delete entity %s for publisher [%s], got:\n%s",
                        (string)$entity->getAliasName(),
                        (string)$publisherName,
                        (string)$e->getMessage()
                    )
                );
            }
        }

        foreach ($this->container->getConsumers() as $consumerAliasName => $entity) {
            try {
                /** @var QueueEntity $entity */
                $entity->delete();
                $this->output->writeln(
                    sprintf(
                        "Deleted entity <info>%s</info> for consumer [<fg=yellow>%s</>]",
                        (string)$entity->getAliasName(),
                        (string)$consumerAliasName
                    )
                );
            } catch (\Exception $e) {
                $hasErrors = true;
                $this->output->error(
                    sprintf(
                        "Could not delete entity %s for consumer [%s], got:\n%s",
                        (string)$entity->getAliasName(),
                        (string)$consumerAliasName,
                        (string)$e->getMessage()
                    )
                );
            }
        }
        return (int)$hasErrors;
    }
}
