<?php
declare(strict_types=1);

namespace App;

use App\Utils\ReflectionUtils;
use Discord\Discord;
use Discord\Exceptions\IntentException;
use Discord\WebSockets\Intents;
use App\Events\EventInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use ReflectionException;

class Main
{
    private Discord $discord;

    public function __construct(
        private readonly ReflectionUtils $reflectionUtils
    )
    {
        try {
            $this->init();
            $this->addEvents();

            $this->discord->run();
        } catch (IntentException|ReflectionException $e) {
            print_r($e->getMessage());
            print_r($e->getTraceAsString());
        }
    }

    /**
     * @throws IntentException
     */
    public function init(): void
    {
        $this->discord = new Discord([
            'token' => $_ENV['DISCORD_BOT_TOKEN'],
            'loadAllMembers' => true,
            'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS | Intents::MESSAGE_CONTENT,
            'logger' => new Logger('discord', [
                new RotatingFileHandler('./logs/discord', 5, Level::Debug, filenameFormat: '{filename}-{date}.log'),
            ])
        ]);
    }

    /**
     * @throws ReflectionException
     */
    private function addEvents(): void
    {
        $events = $this->reflectionUtils->getImplementingClasses(EventInterface::class, 'src/Events');

        foreach ($events as $event) {
            $eventInstance = new $event($this->reflectionUtils);
            $this->discord->on($eventInstance->getEventName(), $eventInstance->handle());
        }
    }
}