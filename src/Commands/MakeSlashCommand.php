<?php

namespace App\Commands;

use Discord\Builders\CommandBuilder;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Interaction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;


#[AsCommand(
    name: 'make:slash-command',
    description: 'Permet de créer une commande slash',
)]
class MakeSlashCommand extends AbstractMakerCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $commandName = strtolower($io->ask('Nom de la commande slash'));
        $className =  ucfirst($commandName) . 'SlashCommand';

        $useStatements = $this->generateUseStatements([
            CommandBuilder::class,
            MessageBuilder::class,
            Discord::class,
            Interaction::class,
        ]);

        $this->generateClassContentFromTemplate('SlashCommand.tpl.txt', "src/SlashCommands/$className.php", [
            'namespace' => 'App\SlashCommands',
            'use_statements' => $useStatements,
            'command_name' => $commandName,
            'className' => $className,
            'command_description' => $io->ask('Description de la commande slash'),
        ]);

        $io->success([
            'Commande créée avec succès',
            "Vous pouvez la tester sur discord avec la commande: /$commandName",
        ]);

        return Command::SUCCESS;
    }
}