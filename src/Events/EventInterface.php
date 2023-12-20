<?php

declare(strict_types=1);

namespace App\Events;

interface EventInterface
{
    public function handle(): \Closure;

    public function getEventName(): string;
}