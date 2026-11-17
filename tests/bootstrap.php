<?php

declare(strict_types=1);

$autoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoload)) {
    require $autoload;
}

$trait = \youvision\enum\think\support\traits\EnumEnhance::class;
if (!trait_exists($trait)) {
    require __DIR__ . '/../src/support/traits/EnumEnhance.php';
}

if (!class_exists(\think\facade\Lang::class, false)) {
    require __DIR__ . '/Stubs/Lang.php';
}

$httpStatusCode = \youvision\enum\think\support\enums\HttpStatusCode::class;
if (!enum_exists($httpStatusCode)) {
    require __DIR__ . '/../src/support/enums/HttpStatusCode.php';
}

$fixtures = [
    \youvision\enum\think\tests\Fixtures\TestIntEnum::class    => 'TestIntEnum',
    \youvision\enum\think\tests\Fixtures\TestStringEnum::class => 'TestStringEnum',
];
foreach ($fixtures as $class => $file) {
    if (!enum_exists($class)) {
        require __DIR__ . '/Fixtures/' . $file . '.php';
    }
}
