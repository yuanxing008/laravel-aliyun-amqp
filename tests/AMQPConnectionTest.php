<?php

namespace JokerProject\LaravelAliyunAmqp;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Tests\JokerProject\LaravelAliyunAmqp\Stubs\ConnectionDetailsStub;
use PHPUnit\Framework\TestCase;

class AMQPConnectionTest extends TestCase
{
//    public function testCreateWithEmptyDetails()
//    {
//        $connection = ConnectionDetailsStub::createConnection('foo', []);
//        $details = $connection->getConnectionDetails();
//        $this->assertEquals('127.0.0.1', $details['hostname']);
//        $this->assertEquals(5672, $details['port']);
//        $this->assertEquals('guest', $details['username']);
//        $this->assertEquals('guest', $details['password']);
//        $this->assertEquals('/', $details['vhost']);
//        $this->assertEquals(true, $details['lazy']);
//        $this->assertEquals(3, $details['read_write_timeout']);
//        $this->assertEquals(3, $details['connect_timeout']);
//        $this->assertEquals(0, $details['heartbeat']);
//    }

    public function testCreateWithAllDetails()
    {
        $aliasName = 'aliyun';
        $config = [
            'hostname' => 'local',
            'port' => 5672,
            'username' => '',
            'password' => '',
            'vhost' => 'ahost',
            'lazy' => false,
            'read_write_timeout' => 3,
            'connect_timeout' => 3,
            'heartbeat' => 0,
            'access_key' => '',
            'access_secret' => '',
            'resource_owner_id' => '',
            'keep_alive' => false,
        ];
        $stub = new ConnectionDetailsStub($aliasName, $config);
        $details = $stub->getConnectionDetails();
        print_r($details);
        $this->assertEquals(5672, $details['port']);
        $this->assertEquals(false, $details['lazy']);
        $this->assertNotEmpty($details['username']);
        $this->assertNotEmpty($details['password']);
        $this->assertEquals(3, $details['read_write_timeout']);
        $this->assertEquals(3, $details['connect_timeout']);
        $this->assertEquals(0, $details['heartbeat']);
    }
}
