<?php
/**
 * Author: Joker
 * Date: 2020-05-08 13:57
 */

namespace JokerProject\LaravelAliyunAmqp;

/**
 * Interface ConsumerInterface
 *
 * @package JokerProject\LaravelAliyunAmqp\Consumer
 * @author  Adrian Tilita <adrian@tilita.ro>
 */
interface ConsumerInterface
{
    /**
     * Consume messages
     *
     * @param int $messages The number of message
     * @param int $seconds  The amount of time a consumer should listen for messages
     * @param int $maxMemory    The amount of memory when a consumer should stop consuming
     * @return mixed
     */
    public function startConsuming(int $messages, int $seconds, int $maxMemory);

    /**
     * Stop the consumer
     */
    public function stopConsuming();
}
