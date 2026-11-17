<?php

declare(strict_types=1);

namespace youvision\enum\think\tests\Fixtures;

use youvision\enum\think\support\traits\EnumEnhance;

enum TestIntEnum: int
{
    use EnumEnhance;

    case ADMIN = 0;
    case MODERATOR = 1;
    case SUBSCRIBER = 2;

    protected function translate(string $key): ?string
    {
        return [
            self::class . '.0' => '管理员',
            self::class . '.1' => '主持人',
            self::class . '.2' => '订阅用户',
        ][$key] ?? null;
    }
}
