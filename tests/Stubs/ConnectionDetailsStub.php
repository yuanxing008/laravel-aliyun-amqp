<?php
namespace Tests\Joker\LaravelAliyunAmqp\Stubs;

use Joker\LaravelAliyunAmqp\AMQPConnection;

class ConnectionDetailsStub extends AMQPConnection
{
    public function __construct($aliasName, array $connectionDetails = [])
    {
        $this->connectionDetails = $connectionDetails;
        $this->aliasName = $aliasName;
        parent::__construct($this->aliasName, $this->connectionDetails);
    }

    public function getConnectionDetails()
    {
        return $this->connectionDetails;
    }
}
