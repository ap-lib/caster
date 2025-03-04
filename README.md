# AP\Caster

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

AP\Caster is a high-performance PHP library designed to facilitate flexible and efficient data casting operations. 
It supports adaptive scalar casting, enum casting, and customizable casting strategies to ensure data integrity and type safety in your applications

## Installation

```bash
composer require ap-lib/caster
```

## Features

- **Adaptive Scalar Casting**: Automatically converts between scalar types (e.g., string to int) when appropriate.
- **Enum Casting**: Seamlessly casts values to PHP enums, ensuring valid enum instances.
- **Customizable Casting Strategies**: Extend or modify casting behavior by implementing custom casters.
- **Error Handling**: Provides detailed error information when casting operations fail.


## Requirements

- PHP 8.3 or higher

## Core Components

AP\Caster is built around a single interface: [`CasterInterface`](src/CasterInterface.php), which defines the contract for all casters.

By default, all casting-related errors extend [`AP\ErrorNode\Error`](https://github.com/ap-lib/error-node/blob/main/src/Error.php), ensuring consistent error handling across different casters.

## Getting started

Here's a quick example demonstrating how to use AP\Caster

### Initialize the PrimaryCaster with desired casters

```php
use AP\Caster\PrimaryCaster;
use AP\Caster\AdaptiveScalarCaster;
use AP\Caster\EnumCaster;

// 
$caster = new PrimaryCaster([
    new AdaptiveScalarCaster(),
    new EnumCaster(),
]);
```

### Example 1: matching data type
```php
$data = "hello world";
$result = $caster->cast("string", $data);

// $result = true
// $data = "hello world"
```

### Example 2: adaptive scalar casting
```php
// 
$data = "1200";
$result = $caster->cast("int", $data);

// $result = true
// $data = 1200
```

### Example 2: enum casting
```php
enum OneTwoThree: string
{
    case One   = 1;
    case Two   = 2;
    case Three = 3;
}

// Example 3: 
$data = 3;
$result = $caster->cast(OneTwoThreeExampleEnumInt::class, $data);

// $result = true
// $data = OneTwoThreeExampleEnumInt::Three
```

### Example 4: handling casting errors
```php
$data = ["hello" => "world"];
$result = $caster->cast("int", $data);

// IMPORTANT, a caster will never modify the original data if casting was unsuccessful
// $data = ["hello" => "world"]    
/* 
    result = [
      \AP\Caster\Error\UnexpectedType::__set_state([
         'message' => 'Unexpected date type, expected `int`, actual `array`',
         'path' => [],
         'expected' => 'int',
         'actual' => 'array',
      ]),
    ]
*/

if (is_array($result)) {
    // Handle errors
    foreach ($result as $error) {
        echo $error->message . PHP_EOL;
    }
}

// Output: Unexpected data type, expected `int`, actual `array`
```