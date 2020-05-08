<?php
/**
 * Author: Joker
 * Date: 2020-05-08 13:57
 */

namespace JokerProject\LaravelAliyunAmqp;

/**
 * Interface PublisherInterface
 *
 * @package JokerProject\LaravelAliyunAmqp\Publisher
 * @author  Adrian Tilita <adrian@tilita.ro>
 */
interface PublisherInterface
{
    /**
     * Publish a new message
     *
     * @param string $message
     * @param string $routingKey
     * @return mixed
     */
    public function publish(string $message, string $routingKey = '');
}
