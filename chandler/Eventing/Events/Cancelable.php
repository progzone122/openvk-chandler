<?php

declare(strict_types=1);

namespace Chandler\Eventing\Events;

interface Cancelable
{
    public function cancel(): void;

    public function isCancelled(): bool;
}
