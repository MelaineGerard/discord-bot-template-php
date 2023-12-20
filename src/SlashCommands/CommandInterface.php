<?php

declare(strict_types=1);
namespace App\SlashCommands;


use Closure;
use Discord\Discord;

interface CommandInterface
{

    public function handle(): Closure;

    public function getCommandName(): string;
    public function getCommandData(Discord $discord): array;
}