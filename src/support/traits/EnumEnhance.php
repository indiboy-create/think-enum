<?php

namespace youvision\enum\think\support\traits;

use think\facade\Lang;

trait EnumEnhance
{
    /**
     * @deprecated 请直接使用枚举原生只读属性 $name
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @deprecated 请直接使用枚举原生只读属性 $value
     */
    public function value(): int|string
    {
        return $this->value;
    }

    /**
     * 读取语言包中的描述，未配置时返回 null。
     * 可覆写此方法以适配其他多语言实现。
     */
    protected function translate(string $key): ?string
    {
        return Lang::has($key) ? Lang::get($key) : null;
    }

    public function description(string $localizationGroup = 'enums'): string
    {
        $key = static::class . '.' . $this->value;

        return $this->translate($key)
            ?? ucfirst(str_replace('_', '', strtolower($this->name)));
    }

    public function is(\BackedEnum $enum): bool
    {
        return $this === $enum;
    }

    public function isNot(\BackedEnum $enum): bool
    {
        return !$this->is($enum);
    }

    /**
     * @param \BackedEnum[] $enums
     */
    public function isAny(array $enums): bool
    {
        return in_array($this, $enums, true);
    }

    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }

    public static function hasName(string $name, bool $strict = false): bool
    {
        return in_array($name, static::names(), $strict);
    }

    public static function hasValue(int|string $value, bool $strict = false): bool
    {
        return in_array($value, static::values(), $strict);
    }

    public static function fromName(string $name): static
    {
        foreach (static::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        throw new \ValueError("$name is not a valid backing name for enum \"" . static::class . '"');
    }

    public static function fromValue(int|string $value): static
    {
        foreach (static::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        throw new \ValueError("$value is not a valid backing value for enum \"" . static::class . '"');
    }

    public static function guess(int|string $key): static
    {
        $valueMatch = null;
        foreach (static::cases() as $case) {
            if ($case->name === $key) {
                return $case;
            }
            if ($valueMatch === null && $case->value === $key) {
                $valueMatch = $case;
            }
        }

        return $valueMatch ?? throw new \ValueError("$key is illegal for enum \"" . static::class . '"');
    }

    public static function toArray(string $localizationGroup = 'enums'): array
    {
        return array_map(fn(\BackedEnum $item) => [
            'name'        => $item->name,
            'value'       => $item->value,
            'description' => $item->description($localizationGroup),
        ], static::cases());
    }

    public static function toSelectArray(string $localizationGroup = 'enums'): array
    {
        return array_reduce(static::toArray($localizationGroup), function ($carry, $item) {
            $carry[$item['value']] = $item['description'];

            return $carry;
        }, []);
    }
}