<?php
namespace Tests\JokerProject\LaravelAliyunAmqp\Stubs;

use JokerProject\LaravelAliyunAmqp\Entity\QueueEntity;

class QueueEntityDetailsStub extends QueueEntity
{
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function __call($methodName, $arguments)
    {
        return $this->$methodName;
    }
}
