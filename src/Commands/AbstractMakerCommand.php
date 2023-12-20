<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;

abstract class AbstractMakerCommand extends Command
{
    public function generateUseStatements(array $classes): string
    {
        $useStatements = '';

        foreach ($classes as $class) {
            $useStatements .= "use $class;\n";
        }

        return $useStatements;
    }

    public function generateClassContentFromTemplate(string $templateName, string $filePath, array $parameters = []): void
    {
        $file = fopen(__DIR__ . '/../../' . $filePath, 'w');
        // on vide le fichier
        $template = file_get_contents(__DIR__ . "/../../maker_templates/$templateName");

        foreach ($parameters as $key => $value) {
            $template = str_replace("{{ $key }}", $value, $template);
        }

        fwrite($file, $template);
        fclose($file);
    }
}