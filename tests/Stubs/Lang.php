<?php

declare(strict_types=1);

namespace think\facade;

class Lang
{
    public static function getLangSet(): string
    {
        return 'zh-cn';
    }

    public static function has(string $key): bool
    {
        return false;
    }

    public static function get(string $key): string
    {
        return '';
    }
}
