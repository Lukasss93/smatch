<p align="center">
    <img style="max-height:400px" src="https://banners.beyondco.de/Smatch.png?theme=dark&packageManager=composer+require&packageName=lukasss93%2Fsmatch&pattern=charlieBrown&style=style_1&description=Match+for+PHP+7.3+and+PHP+7.4+&md=0&showWatermark=0&fontSize=155px&images=menu-alt-2"/>
</p>

# Smatch

[![Version](https://poser.pugx.org/lukasss93/smatch/v/stable)](https://packagist.org/packages/lukasss93/smatch)
[![Downloads](https://poser.pugx.org/lukasss93/smatch/downloads)](https://packagist.org/packages/lukasss93/smatch)
![PHP](https://img.shields.io/badge/PHP-7.3%20|%207.4-blue)
![GitHub](https://img.shields.io/github/license/lukasss93/smatch)
[![Build](https://img.shields.io/github/workflow/status/Lukasss93/smatch/run-tests)](https://github.com/Lukasss93/smatch/actions/workflows/tests.yml)
[![Codecov](https://img.shields.io/codecov/c/github/lukasss93/smatch?token=U2YNDTL8GX)](https://codecov.io/gh/Lukasss93/smatch)

> Match for PHP 7.3 and PHP 7.4

## 🚀 Installation

You can install the package using composer:

```bash
composer require lukasss93/smatch
```

## 👓 Usage

**Structure of a smatch function**

```php
$result = smatch('apple')
    ->case('pear', 'tasty')
    ->case('apple', 'delicious')
    ->case('banana', 'yellow')
    ->get();

// $result = 'delicious'
```

**Case methods value can accept a closure too.**

```php
$result = smatch('apple')
    ->case('pear', fn () => 'tasty')
    ->case('apple', fn () => 'delicious')
    ->case('banana', fn () => 'yellow')
    ->get();

// $result = 'delicious'
```

**Case method may contain an array of values**

```php
$result = smatch('chair')
    ->case(['apple', 'pear', 'banana'], 'fruit')
    ->case(['table', 'chair'], 'furniture')
    ->get();

// $result = 'furniture'
```

**A special case is the fallback pattern. This pattern matches anything that wasn't previously matched.**

```php
$result = smatch('strawberry')
    ->case('pear', 'tasty')
    ->case('apple', 'delicious')
    ->case('banana', 'yellow')
    ->fallback('invalid')
    ->get();
    
// $result = 'invalid'
```

**Fallback method can accept a closure too.**

```php
$result = smatch('strawberry')
    ->case('pear', 'tasty')
    ->case('apple', 'delicious')
    ->case('banana', 'yellow')
    ->fallback(fn () => 'invalid')
    ->get();
    
// $result = 'invalid'
```

> Note: Multiple fallback methods will override the last one.

A smatch function must be exhaustive. If the subject function is not handled by any case method an
UnhandledSmatchException is thrown.

**Example of an unhandled smatch function**

```php
try {
    $result = smatch('strawberry')
        ->case('pear', 'tasty')
        ->case('apple', 'delicious')
        ->case('banana', 'yellow')
        ->get();
} catch (UnhandledSmatchException $e){
    echo $e->getMessage();
}

// $e->getMessage() = Unhandled smatch value of type string
```

**Using getOr method to handle missing fallback method**

```php
$result = smatch('car')
    ->case('pear', 'tasty')
    ->case('apple', 'delicious')
    ->case('banana', 'yellow')
    ->getOr(fn () => 'complex logic');

// $result = 'complex logic'
```

#### Using smatch function to handle non identity checks

It is possible to use a smatch function to handle non-identity conditional cases by using true as the subject function.

**Using a generalized smatch function to branch on integer ranges**

```php
$age = 23;

$result = smatch(true)
    ->case($age >= 65, 'senior')
    ->case($age >= 25, 'adult')
    ->case($age >= 18, 'young adult')
    ->fallback('kid')
    ->get();

// $result = 'young adult'
```

**Using a generalized smatch function to branch on string content**

```php
$text = 'Bienvenue chez nous';

$result = smatch(true)
    ->case(str_contains($text, 'Welcome') || str_contains($text, 'Hello'), 'en')
    ->case(str_contains($text, 'Bienvenue') || str_contains($text, 'Bonjour'), 'fr')
    ->get();

// $result = 'fr'
```

## ⚗️ Testing

```bash
composer test
```

## 📃 Changelog

Please see the [CHANGELOG.md](CHANGELOG.md) for more information on what has changed recently.

## 🏅 Credits

- [Luca Patera](https://github.com/Lukasss93)
- [All Contributors](https://github.com/Lukasss93/smatch/contributors)

## 📖 License

Please see the [LICENSE.md](LICENSE.md) file for more information.
