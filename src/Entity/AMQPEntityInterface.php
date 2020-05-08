<?php
/**
 * Author: Joker
 * Date: 2020-05-08 13:57
 */

namespace Joker\LaravelAliyunAmqp\Entity;

/**
 * Interface AMQPEntityInterface
 *
 * @package Joker\LaravelAliyunAmqp\Entity
 */
interface AMQPEntityInterface
{
    /**
     * Create the entity
     * @return mixed
     */
    public function create();

    /**
     * Bind the entity
     * @return void
     */
    public function bind();

    /**
     * @return void
     */
    public function delete();

    /**
     * @return string
     */
    public function getAliasName();

    /**
     * Reconnect the entity
     */
    public function reconnect();
}
