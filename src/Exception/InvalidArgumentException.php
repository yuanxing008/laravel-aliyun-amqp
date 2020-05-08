<?php
/**
 * Author: Joker
 * Date: 2020-05-08 13:57
 */

namespace JokerProject\LaravelAliyunAmqp\Exception;

/**
 * Class InvalidArgumentException
 *
 * @package JokerProject\LaravelAliyunAmqp\Exception
 * @codeCoverageIgnore
 */
class InvalidArgumentException extends \InvalidArgumentException implements LaravelRabbitMqException
{
}
