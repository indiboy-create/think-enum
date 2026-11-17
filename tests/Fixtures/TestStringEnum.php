<?php

declare(strict_types=1);

namespace youvision\enum\think\tests\Fixtures;

use youvision\enum\think\support\traits\EnumEnhance;

enum TestStringEnum: string
{
    use EnumEnhance;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
