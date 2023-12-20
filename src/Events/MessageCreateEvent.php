<?php

declare(strict_types=1);
namespace App\Events;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;

class MessageCreateEvent implements EventInterface
{

    public function handle(): \Closure
    {
        return function (Message $message, Discord $discord) {
            if ($message->author->id === $discord->id) {
                return;
            }

            if (str_contains(strtolower($message->content), "bonjour")) {
                $message->react("👋");
            }
        };
    }

    public function getEventName(): string
    {
        return Event::MESSAGE_CREATE;
    }
}