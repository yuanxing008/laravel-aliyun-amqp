<?php
namespace Tests\JokerProject\LaravelAliyunAmqp\Stubs;

use JokerProject\LaravelAliyunAmqp\AMQPConnection;

class ConnectionDetailsStub extends AMQPConnection
{
    public function __construct($aliasName, array $connectionDetails = [])
    {
        $this->connectionDetails = $connectionDetails;
        $this->aliasName = $aliasName;
    }

    public function getConnectionDetails()
    {
        return $this->connectionDetails;
    }
}
