<?php

namespace App\Utils;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;

class DatabaseSetupUtils
{
    private EntityManager $entityManager;
    private static ?DatabaseSetupUtils $instance = null;

    public static function getInstance(): DatabaseSetupUtils
    {
        if (!self::$instance instanceof DatabaseSetupUtils) {
            self::$instance = new DatabaseSetupUtils();
        }

        return self::$instance;
    }

    public function __construct()
    {
        try {
            $this->initDoctrine();
        } catch (Exception | MissingMappingDriverImplementation $e) {
            echo $e->getMessage() . PHP_EOL;
            echo $e->getTraceAsString() . PHP_EOL;

            exit(1);
        }
    }

    /**
     * @throws Exception
     * @throws MissingMappingDriverImplementation
     */
    private function initDoctrine(): void
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [__DIR__ . '/../Entities'],
            isDevMode: true,
        );

        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '../../../db.sqlite',
        ], $config);

        $this->entityManager = new EntityManager($connection, $config);
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }
}