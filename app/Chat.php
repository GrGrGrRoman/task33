<?php

namespace App;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require "../db/Users.php";
require "../db/Chatrooms.php";

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Server Started.";
    }

    static public function d($str): void
    	{
        echo '<pre>';
        var_dump($str);
        echo '</pre>';
    	}

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        $data = json_decode($msg, true);
        $objChatroom = new \Chatrooms;
        $objChatroom->setUserId($data['userId']);
        $objChatroom->setMsg($data['msg']);
        $objChatroom->setCreatedOn(date("Y-m-d h:i:s"));
        if($objChatroom->saveChatRoom())
        {
            $objUser = new \Users;
            $objUser->setId($data['userId']);
            $user = $objUser->getUserById();
            $data['from'] = ($user['name'] !== $user['email'] and $user['hide_email'] == 1) ? $user['name'] : $user['email'];
            $data['msg']  = $data['msg'];
            $data['dt']  = date("d.m.Y h:i");
        }

        foreach ($this->clients as $client) {
            if ($from == $client)
            {
                $data['from']  = "Я";
            }
            else
            {
                $data['from']  = ($user['name'] !== $user['email'] and $user['hide_email'] == 1) ? $user['name'] : $user['email'];
            }
            $client->send(json_encode($data));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}