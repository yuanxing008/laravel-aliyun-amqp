<?php
namespace Tests\JokerProject\LaravelAliyunAmqp\Stubs;

use JokerProject\LaravelAliyunAmqp\Entity\ExchangeEntity;

class ExchangeEntityDetailsStub extends ExchangeEntity
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
