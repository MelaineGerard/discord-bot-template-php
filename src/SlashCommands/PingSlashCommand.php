<?php

declare(strict_types=1);
namespace App\SlashCommands;

use Discord\Builders\CommandBuilder;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Interaction;

class PingSlashCommand implements CommandInterface
{
    public function handle(): \Closure
    {
        return function (Interaction $interaction) {
            $interaction->acknowledgeWithResponse(true)->done();
            $embed = new Embed($interaction->getDiscord());
            $embed->setTitle("🏓 Pong !");
            $embed->setColor(0xA62639);
            $interaction->updateOriginalResponse(
                MessageBuilder::new()
                    ->addEmbed($embed)
            )->done();
        };
    }

    public function getCommandName(): string
    {
        return "ping";
    }

    public function getCommandData(Discord $discord): array
    {
        return CommandBuilder::new()
            ->setName($this->getCommandName())
            ->setDescription("Pongs !")
            ->toArray();
    }
}