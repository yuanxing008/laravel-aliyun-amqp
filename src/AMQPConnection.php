<?php
/**
 * Author: Joker
 * Date: 2020-05-08 13:57
 */

namespace Joker\LaravelAliyunAmqp;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPSocketConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class AMQPConnection
 *
 * @package Joker\LaravelAliyunAmqp
 */
class AMQPConnection
{
    /**
     * @const array Default connections parameters
     */
    const DEFAULTS = [
        'hostname' => '127.0.0.1',
        'port' => 5672,
        'username' => 'guest',
        'password' => 'guest',
        'vhost' => '/',

        # whether the connection should be lazy
        'lazy' => true,

        # More info about timeouts can be found on https://www.rabbitmq.com/networking.html
        'read_write_timeout' => 3,   // default timeout for writing/reading (in seconds)
        'connect_timeout' => 3,
        'heartbeat' => 0,
        'keep_alive' => false,
        'access_key' => '',
        'access_secret' => '',
        'resource_owner_id' => '',
    ];

    /**
     * @var array
     */
    protected $connectionDetails = [];

    /**
     * @var string
     */
    protected $aliasName = '';

    /**
     * @var null|AbstractConnection
     */
    private $connection = null;

    /**
     * @var null|AMQPChannel
     */
    private $channel = null;

    /**
     * @var string
     */
    private $accessKey = '';

    /**
     * @var string
     */
    private $accessSecret = '';

    /**
     * @var string
     */
    private $resourceOwnerId = '';

    /**
     * @param string $aliasName
     * @param array  $connectionDetails
     *
     * @return AMQPConnection
     */
    public static function createConnection(string $aliasName, array $connectionDetails)
    {
        if ($diff = array_diff(array_keys($connectionDetails), array_keys(self::DEFAULTS))) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Cannot create connection %s, received unknown arguments: %s!",
                    (string)$aliasName,
                    implode(', ', $diff)
                )
            );
        }
        return new static(
            $aliasName,
            array_merge(self::DEFAULTS, $connectionDetails)
        );
    }

    /**
     * AMQPConnection constructor.
     *
     * @param string $aliasName
     * @param array  $connectionDetails
     * @param string $accessKey
     * @param string $accessSecret
     * @param string $resourceOwnerId
     */
    public function __construct(
        string $aliasName,
        array $connectionDetails = []
    ) {
        $this->aliasName = $aliasName;
        $this->accessKey = $connectionDetails['access_key'];
        $this->accessSecret = $connectionDetails['access_secret'];
        $this->resourceOwnerId = $connectionDetails['resource_owner_id'];
        if ($connectionDetails['access_key'] != ''
            && $connectionDetails['access_secret'] != ''
            && $connectionDetails['resource_owner_id'] != ''
        ) {
            $connectionDetails['username'] = $this->getUser();
            $connectionDetails['password'] = $this->getPassword();
        }
        $this->connectionDetails = $connectionDetails;
        if (isset($connectionDetails['lazy']) && $connectionDetails['lazy'] === false) {
            $this->getConnection();
        }
    }

    /**
     * @return AbstractConnection
     */
    protected function getConnection(): AbstractConnection
    {
        if (is_null($this->connection)) {
            if (!isset($this->connection['type'])) {
                $this->connection['type'] = AMQPStreamConnection::class;
            }
            switch ($this->connection['type']) {
                case AMQPStreamConnection::class:
                case 'stream':
                    $type = AMQPStreamConnection::class;
                    break;
                default:
                    $type = AMQPSocketConnection::class;
            }

            $this->connection = $this->createConnectionByType($type);
        }
        return $this->connection;
    }

    /**
     * @param $type
     *
     * @return mixed
     */
    private function createConnectionByType($type)
    {
        return new $type(
            $this->connectionDetails['hostname'],
            $this->connectionDetails['port'],
            $this->connectionDetails['username'],
            $this->connectionDetails['password'],
            $this->connectionDetails['vhost'],
            /** insist */
            false,
            /** login method */
            'AMQPLAIN',
            /** login_response */
            null,
            /** locale */
            'en_US',
            $this->connectionDetails['connect_timeout'],
            $this->connectionDetails['read_write_timeout'],
            null,
            $this->connectionDetails['keep_alive'],
            $this->connectionDetails['heartbeat']
        );
    }

    /**
     * Reconnect
     */
    public function reconnect()
    {
        $this->getConnection()->channel()->close();
        $this->channel = null;
        $this->getConnection()->reconnect();
    }

    /**
     * @return \PhpAmqpLib\Channel\AMQPChannel
     */
    public function getChannel()
    {
        if (is_null($this->channel)) {
            $this->channel = $this->getConnection()->channel();
        }
        return $this->channel;
    }

    /**
     * Retrieve the connection alias name
     *
     * @return string
     */
    public function getAliasName(): string
    {
        return $this->aliasName;
    }


    /**
     * getUser
     *
     * @return string
     */
    private function getUser()
    {
        $t = '0:' . $this->resourceOwnerId . ':' . $this->accessKey;
        return base64_encode($t);
    }

    /**
     * getPassword
     *
     * @return string
     */
    private function getPassword()
    {
        $ts = (int)(microtime(true) * 1000);
        $value = utf8_encode($this->accessSecret);
        $key = utf8_encode((string)$ts);
        $sig = strtoupper(hash_hmac('sha1', $value, $key, false));
        return base64_encode(utf8_encode($sig . ':' . $ts));
    }
}
