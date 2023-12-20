<?php
declare(strict_types=1);
namespace App\Utils;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
use SplFileInfo;

class ReflectionUtils
{
    /**
     * @throws ReflectionException
     */
    public function getImplementingClasses(string $interfaceName, string $fromDirectory): array
    {
        $dir = new RecursiveDirectoryIterator(__DIR__ . '/../../' . $fromDirectory);
        $ite = new RecursiveIteratorIterator($dir);
        $classes = [];

        foreach ($ite as $phpFile) {
            if (!str_ends_with($phpFile->getFilename(), '.php') || str_contains($phpFile->getFilename(), 'Abstract') || str_contains($phpFile->getFilename(), 'Interface')) {
                continue;
            }

            include_once $phpFile->getRealPath();

            $className = str_replace('.php', '', $phpFile->getFilename());
            foreach (get_declared_classes() as $class) {
                $reflectionClass = new ReflectionClass($class);


                $interfaces = $reflectionClass->getInterfaceNames();

                if (in_array($interfaceName, $interfaces)) {
                    $classes[] = $class;
                }
            }
        }

        return $classes;
    }

    /**
     * @throws ReflectionException
     */
    public function getExtendingClasses(string $parentName, string $fromDirectory): array
    {
        // on récupère tous les fichiers php du dossier $fromDirectory à partir de la racine du projet en checkant aussi les sous-dossiers
        $dir = new RecursiveDirectoryIterator(__DIR__ . '/../../' . $fromDirectory);
        $ite = new RecursiveIteratorIterator($dir);
        $classes = [];

        /** @var SplFileInfo $phpFile */
        foreach ($ite as $phpFile) {
            if (!str_ends_with($phpFile->getFilename(), '.php') || str_contains($phpFile->getFilename(), 'Abstract') || str_contains($phpFile->getFilename(), 'Interface')) {
                continue;
            }

            include_once $phpFile->getRealPath();

            $className = str_replace('.php', '', $phpFile->getFilename());

            foreach (get_declared_classes() as $class) {
                $reflectionClass = new ReflectionClass($class);

                if (!$reflectionClass->getParentClass() instanceof ReflectionClass) {
                    continue;
                }

                if ($reflectionClass->getShortName() === $className && $reflectionClass->isSubclassOf($parentName)) {
                    $classes[] = $class;
                }
            }
        }

        return $classes;
    }
}