<?php

namespace App\Http\Controllers;

use BeyondCode\LaravelWebSockets\WebSockets\Messages\PusherMessageFactory;
use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;

class CommentLogSocketHandler extends WebSocketHandler
{
  public function onMessage(ConnectionInterface $connection, MessageInterface $message)
  {
    $message = PusherMessageFactory::createForMessage($message, $connection, $this->channelManager);

    $message->respond();
  }

  public function onClose(ConnectionInterface $connection)
  {
    $this->channelManager->removeFromAllChannels($connection);
  }
}
