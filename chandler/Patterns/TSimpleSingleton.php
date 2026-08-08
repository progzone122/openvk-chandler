<?php

declare(strict_types=1);

namespace Chandler\Patterns;

trait TSimpleSingleton
{
    private static ?self $self = null;

    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}

    public static function i(): static
    {
        return static::$self ?? static::$self = new static();
    }
}
