# think-enum

基于 ThinkPHP 8 的 PHP 枚举增强工具，通过合理地定义枚举使代码更加规范、易读、易维护。

PHP 8.1+ 内置了原生枚举（Enums）支持，更多信息可参考：[PHP 枚举文档](https://www.php.net/manual/zh/language.enumerations.php)。

## 概览

- 扩展原生 `BackedEnum`，支持多语言描述
- 提供更多实用的方式来实例化枚举、获取枚举 name / value
- 提供便捷的比较方法 `is`、`isNot`、`isAny`，用于枚举实例之间的比较
- 支持 ThinkPHP 8

## 安装

环境要求：PHP 8.1+，ThinkPHP 8+

```shell
composer require youvision/think-enum
```

## 常规使用

### 定义枚举

```php
<?php

namespace app\enums;

use youvision\enum\think\support\traits\EnumEnhance;

enum UserType: int
{
    use EnumEnhance;

    case ADMINISTRATOR = 0;
    case MODERATOR = 1;
    case SUBSCRIBER = 2;
}
```

### 基础取值

```php
// 获取枚举的值
UserType::ADMINISTRATOR->value; // 0

// 获取所有已定义枚举的名称
$names = UserType::names(); // ['ADMINISTRATOR', 'MODERATOR', 'SUBSCRIBER']

// 获取所有已定义枚举的值
$values = UserType::values(); // [0, 1, 2]
```

### 枚举校验

```php
UserType::hasValue(1);          // true
UserType::hasValue(-1);         // false
UserType::hasName('MODERATOR'); // true
UserType::hasName('ADMIN');     // false
```

### 枚举实例化

```php
$enum = UserType::fromName('MODERATOR');  // 根据名称实例化
$enum = UserType::fromValue(1);           // 根据值实例化
$enum = UserType::guess('ADMINISTRATOR'); // 自动根据名称或值实例化

$enum->name;          // 'MODERATOR'（原生只读属性，推荐）
$enum->value;         // 1（原生只读属性，推荐）
$enum->description(); // '主持人'（根据语言包返回描述）
```

若传入非法的名称或值，将抛出 `\ValueError` 异常。

为兼容旧版本，trait 仍保留 `name()` 与 `value()` 方法，但已标记为废弃，建议使用原生属性。

### 枚举比较

```php
$enum = UserType::ADMINISTRATOR;

$enum->is(UserType::ADMINISTRATOR);                            // true
$enum->isNot(UserType::MODERATOR);                             // true
$enum->isAny([UserType::ADMINISTRATOR, UserType::SUBSCRIBER]); // true
```

### toArray

```php
$array = UserType::toArray();
/*
[
    ['name' => 'ADMINISTRATOR', 'value' => 0, 'description' => '管理员'],
    ['name' => 'MODERATOR',     'value' => 1, 'description' => '主持人'],
    ['name' => 'SUBSCRIBER',    'value' => 2, 'description' => '订阅用户'],
]
*/
```

### toSelectArray

```php
$array = UserType::toSelectArray(); // 支持多语言配置
/*
[
    0 => '管理员',
    1 => '主持人',
    2 => '订阅用户',
]
*/
```

## 多语言描述

在应用语言目录（如 `lang/zh-cn/`）下创建 `enums.php` 文件，以枚举类名作为键，枚举值对应描述：

```php
<?php

use app\enums\UserType;

return [
    UserType::class => [
        UserType::ADMINISTRATOR->value => '管理员',
        UserType::MODERATOR->value     => '主持人',
        UserType::SUBSCRIBER->value    => '订阅用户',
    ],
];
```

调用 `description()`、`toArray()` 或 `toSelectArray()` 时，会依据当前语言集自动读取对应语言包中的描述；未配置时返回根据枚举 name 生成的默认描述。

可通过 `$localizationGroup` 参数指定语言分组文件名，默认为 `enums`：

```php
UserType::toSelectArray('enums'); // 读取 lang/{langset}/enums.php
```

## 内置枚举

包内提供常用枚举，可直接使用：

```php
use youvision\enum\think\support\enums\HttpStatusCode;

HttpStatusCode::HTTP_OK;        // 枚举实例，value 为 200
HttpStatusCode::HTTP_NOT_FOUND; // 枚举实例，value 为 404
```

## 开发

```shell
composer install
composer test
```

测试不依赖真实 ThinkPHP 应用环境：语言包解析通过可覆写的 `translate()` 方法进行替身。

## License

MIT
