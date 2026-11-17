<?php

declare(strict_types=1);

namespace youvision\enum\think\tests;

use PHPUnit\Framework\TestCase;
use youvision\enum\think\support\enums\HttpStatusCode;
use youvision\enum\think\tests\Fixtures\TestIntEnum;
use youvision\enum\think\tests\Fixtures\TestStringEnum;

final class EnumEnhanceTest extends TestCase
{
    public function testNativeNameAndValue(): void
    {
        self::assertSame('MODERATOR', TestIntEnum::MODERATOR->name);
        self::assertSame(1, TestIntEnum::MODERATOR->value);
    }

    public function testNamesAndValues(): void
    {
        self::assertSame(['ADMIN', 'MODERATOR', 'SUBSCRIBER'], TestIntEnum::names());
        self::assertSame([0, 1, 2], TestIntEnum::values());
    }

    public function testHasNameAndHasValue(): void
    {
        self::assertTrue(TestIntEnum::hasName('MODERATOR'));
        self::assertFalse(TestIntEnum::hasName('NOT_EXIST'));
        self::assertTrue(TestIntEnum::hasValue(1));
        self::assertFalse(TestIntEnum::hasValue(999));
    }

    public function testFromName(): void
    {
        self::assertSame(TestIntEnum::ADMIN, TestIntEnum::fromName('ADMIN'));
    }

    public function testFromNameThrowsValueError(): void
    {
        $this->expectException(\ValueError::class);
        TestIntEnum::fromName('NOT_EXIST');
    }

    public function testFromValue(): void
    {
        self::assertSame(TestIntEnum::MODERATOR, TestIntEnum::fromValue(1));
    }

    public function testFromValueThrowsValueErrorForNumericString(): void
    {
        $this->expectException(\ValueError::class);
        TestIntEnum::fromValue('1');
    }

    public function testGuessByName(): void
    {
        self::assertSame(TestIntEnum::SUBSCRIBER, TestIntEnum::guess('SUBSCRIBER'));
    }

    public function testGuessByValue(): void
    {
        self::assertSame(TestIntEnum::SUBSCRIBER, TestIntEnum::guess(2));
    }

    public function testGuessThrowsValueErrorForNumericString(): void
    {
        $this->expectException(\ValueError::class);
        TestIntEnum::guess('1');
    }

    public function testStringBackedEnum(): void
    {
        self::assertSame('active', TestStringEnum::ACTIVE->value);
        self::assertSame(TestStringEnum::ACTIVE, TestStringEnum::fromValue('active'));
        self::assertSame(TestStringEnum::ACTIVE, TestStringEnum::guess('ACTIVE'));
    }

    public function testIsAndIsNot(): void
    {
        self::assertTrue(TestIntEnum::ADMIN->is(TestIntEnum::ADMIN));
        self::assertFalse(TestIntEnum::ADMIN->is(TestIntEnum::MODERATOR));
        self::assertTrue(TestIntEnum::ADMIN->isNot(TestIntEnum::MODERATOR));
    }

    public function testIsAny(): void
    {
        self::assertTrue(TestIntEnum::ADMIN->isAny([TestIntEnum::ADMIN, TestIntEnum::MODERATOR]));
        self::assertFalse(TestIntEnum::ADMIN->isAny([TestIntEnum::MODERATOR]));
    }

    public function testDescription(): void
    {
        self::assertSame('主持人', TestIntEnum::MODERATOR->description());
    }

    public function testDescriptionFallback(): void
    {
        self::assertSame('Active', TestStringEnum::ACTIVE->description());
        self::assertSame('Httpok', HttpStatusCode::HTTP_OK->description());
    }

    public function testToArray(): void
    {
        self::assertSame([
            ['name' => 'ADMIN', 'value' => 0, 'description' => '管理员'],
            ['name' => 'MODERATOR', 'value' => 1, 'description' => '主持人'],
            ['name' => 'SUBSCRIBER', 'value' => 2, 'description' => '订阅用户'],
        ], TestIntEnum::toArray());
    }

    public function testToSelectArray(): void
    {
        self::assertSame([
            0 => '管理员',
            1 => '主持人',
            2 => '订阅用户',
        ], TestIntEnum::toSelectArray());
    }
}
