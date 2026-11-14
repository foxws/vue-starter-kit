<?php

declare(strict_types=1);

namespace Domain\Shared\DataObjects;

use Domain\Shared\Contracts\DescribesState;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class StateData extends Data
{
    public function __construct(
        public string $name,
        public string $label,
        public string $icon,
        public string $color,
    ) {}

    public static function fromState(DescribesState $state): self
    {
        return new self(
            name: $state->getValue(),
            label: $state->label(),
            icon: $state->icon(),
            color: $state->color(),
        );
    }
}
