<?php

namespace App\WebSocket;

use Psr\Log\LoggerInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * Class NotificationServer
 *
 * @package App\WebSocket
 */
class NotificationServer implements MessageComponentInterface
{
    /** @var \SplObjectStorage */
    protected $clients;

    /** @var LoggerInterface */
    private $logger;

    /**
     * NotificationServer constructor.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->clients = new \SplObjectStorage;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onOpen(ConnectionInterface $connection)
    {
        $this->clients->attach($connection);
    }

    /**
     * @param ConnectionInterface $from
     * @param $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->clients as $client) {
            if ($from === $client) {
                continue;
            }

            $client->send($msg);
        }
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }
}
