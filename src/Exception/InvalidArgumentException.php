<?php
/**
 * Author: Joker
 * Date: 2020-05-08 13:57
 */

namespace Joker\LaravelAliyunAmqp\Exception;

/**
 * Class InvalidArgumentException
 *
 * @package Joker\LaravelAliyunAmqp\Exception
 * @codeCoverageIgnore
 */
class InvalidArgumentException extends \InvalidArgumentException implements LaravelRabbitMqException
{
}
