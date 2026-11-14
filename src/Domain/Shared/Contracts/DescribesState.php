<?php

declare(strict_types=1);

namespace Domain\Shared\Contracts;

interface DescribesState
{
    public function getValue(): string;

    public function label(): string;

    public function icon(): string;

    public function color(): string;
}
