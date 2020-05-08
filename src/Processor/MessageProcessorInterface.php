<?php
/**
 * Author: Joker
 * Date: 2020-05-08 13:57
 */

namespace JokerProject\LaravelAliyunAmqp\Processor;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Interface MessageProcessorInterface
 *
 * @package JokerProject\LaravelAliyunAmqp\Processor
 * @author  Adrian Tilita <adrian@tilita.ro>
 */
interface MessageProcessorInterface
{
    /**
     * @param AMQPMessage $message
     * @return mixed
     */
    public function consume(AMQPMessage $message);

    /**
     * @return int
     */
    public function getProcessedMessages(): int;
}
