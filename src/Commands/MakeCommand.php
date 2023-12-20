<?php

namespace App\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:command',
    description: 'Permet de créer une commande',
)]
class MakeCommand extends AbstractMakerCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $commandName = trim($io->ask('Nom de la commande'));
        $className = str_replace('-', '', str_replace(':', '', ucwords($commandName, ':-'))) . 'Command';

        $useStatements = $this->generateUseStatements([
            Command::class,
            InputInterface::class,
            OutputInterface::class,
            SymfonyStyle::class,
            AsCommand::class,
        ]);

        $this->generateClassContentFromTemplate('Command.tpl.txt', "src/Commands/{$className}.php", [
            'namespace' => 'App\Commands',
            'use_statements' => $useStatements,
            'command_name' => $commandName,
            'className' => $className,
        ]);

        $io->success([
            'Commande créée avec succès',
            "Vous pouvez l'exécuter avec la commande: php bin/console $commandName",
        ]);

        return Command::SUCCESS;
    }
}