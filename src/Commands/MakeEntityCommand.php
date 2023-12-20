<?php

namespace App\Commands;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;


#[AsCommand(
    name: 'make:entity',
    description: 'Permet de créer une entité',
)]
class MakeEntityCommand extends AbstractMakerCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $entityName = ucfirst($io->ask('Nom de l\'entité'));

        $useStatementRepository = $this->generateUseStatements([
            EntityRepository::class
        ]);

        $this->generateClassContentFromTemplate('Repository.tpl.txt', "src/Repositories/{$entityName}Repository.php", [
            'className' => $entityName . 'Repository',
            'useStatements' => $useStatementRepository,
            'namespace' => 'App\Repositories'
        ]);

        $useStatementEntity = $this->generateUseStatements([
            Entity::class,
            Table::class,
            'App\Repositories\\' . $entityName . 'Repository'
        ]);

        $this->generateClassContentFromTemplate('Entity.tpl.txt', 'src/Entities/' . $entityName . '.php', [
            'className' => $entityName,
            'useStatements' => $useStatementEntity,
            'namespace' => 'App\Entities',
            'tableName' => strtolower($entityName) . 's',
        ]);

        $io->success('Entité créée avec succès !');

        return Command::SUCCESS;
    }
}