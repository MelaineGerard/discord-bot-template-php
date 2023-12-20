<?php

namespace App\Commands;

use Discord\Discord;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;


#[AsCommand(
    name: 'make:event',
    description: 'Permet de créer un event listener',
)]
class MakeEventCommand extends AbstractMakerCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $eventName = $io->ask("Quel est le nom de l'event ? (ex: init ou MESSAGE_CREATE)");
        $className = str_replace('_', '', ucwords(strtolower($eventName), '_')) . 'Event';

        $useStatements = [
            \Closure::class,
            Discord::class,
        ];

        $this->generateClassContentFromTemplate('Listener.tpl.txt', "src/Events/{$className}.php", [
            'namespace' => 'App\Events',
            'use_statements' => $this->generateUseStatements($useStatements),
            'eventName' => $eventName,
            'className' => $className,
        ]);

        $io->success([
            'Event créé avec succès !',
        ]);

        return Command::SUCCESS;
    }
}