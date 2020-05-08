<?php
/**
 * Author: Joker
 * Date: 2020-05-08 13:57
 */

namespace Joker\LaravelAliyunAmqp\Processor;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class CliOutputProcessor
 *
 * @package Joker\LaravelAliyunAmqp\Processor
 * @author  Adrian Tilita <adrian@tilita.ro>
 * @codeCoverageIgnore
 */
class CliOutputProcessor extends AbstractMessageProcessor
{
    /**
     * @param AMQPMessage $message
     * @return bool
     */
    public function processMessage(AMQPMessage $message): bool
    {
        echo $message->getBody() . "\n";
        return true;
    }
}
