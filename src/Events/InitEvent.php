<?php

declare(strict_types=1);
namespace App\Events;

use App\SlashCommands\CommandInterface;
use App\Utils\ReflectionUtils;
use Closure;
use Discord\Discord;
use Discord\Parts\Interactions\Command\Command;
use Exception;
use ReflectionException;

class InitEvent implements EventInterface
{
    public function __construct(
        private readonly ReflectionUtils $reflectionUtils
    )
    {}

    public function handle(): Closure
    {
        return function (Discord $discord) {
            $this->registerCommands($discord);
            $discord->getLogger()->info(sprintf("Connected on discord as %s#%s (Id: %s) !", $discord->username, $discord->discriminator, $discord->id));
        };
    }

    public function getEventName(): string
    {
        return "init";
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    private function registerCommands(Discord $discord): void
    {
        $guild = $discord->guilds->get('id', "1033659360863342662");
        $commands = $this->reflectionUtils->getImplementingClasses(CommandInterface::class, 'src/Commands');

        foreach ($commands as $command) {
            $commandInstance = new $command($this->reflectionUtils);

            $guild->commands->save(
                new Command(
                    $discord,
                    $commandInstance->getCommandData($discord)
                )
            );

            $discord->listenCommand($commandInstance->getCommandName(), $commandInstance->handle());
        }
    }
}