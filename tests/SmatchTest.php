<?php

namespace Lukasss93\Smatch\Tests;

use Lukasss93\Smatch\Exceptions\UnhandledSmatchException;

class SmatchTest extends TestCase
{
    public function testMatch(): void
    {
        $result = smatch('apple')
            ->case('pear', 'tasty')
            ->case('apple', 'delicious')
            ->case('banana', 'yellow')
            ->fallback('invalid')
            ->get();

        self::assertEquals('delicious', $result);
    }

    public function testMatchWithMultipleConditions(): void
    {
        $result = smatch('table')
            ->case(['apple', 'pear', 'banana'], 'fruit')
            ->case(['table', 'chair'], 'furniture')
            ->fallback('invalid')
            ->get();

        self::assertEquals('furniture', $result);
    }

    public function testMatchWithClosure(): void
    {
        $obj = new class() {
            public function isTasty(): string
            {
                return 'tasty';
            }

            public function isDelicious(): string
            {
                return 'delicious';
            }

            public function isYellow(): string
            {
                return 'yellow';
            }
        };

        $result = smatch('apple')
            ->case('pear', function () use ($obj) {
                return $obj->isTasty();
            })
            ->case('apple', function () use ($obj) {
                return $obj->isDelicious();
            })
            ->case('banana', function () use ($obj) {
                return $obj->isYellow();
            })
            ->fallback('invalid')
            ->get();

        self::assertEquals('delicious', $result);
    }

    public function testFallback(): void
    {
        $result = smatch('strawberry')
            ->case('pear', 'tasty')
            ->case('apple', 'delicious')
            ->case('banana', 'yellow')
            ->fallback('invalid')
            ->get();

        self::assertEquals('invalid', $result);
    }

    public function testFallbackWithClosure(): void
    {
        $result = smatch('strawberry')
            ->case('pear', 'tasty')
            ->case('apple', 'delicious')
            ->case('banana', 'yellow')
            ->fallback(function () {
                return 'invalid';
            })
            ->get();

        self::assertEquals('invalid', $result);
    }

    public function testUnhandledFallback(): void
    {
        $this->expectException(UnhandledSmatchException::class);

        smatch('strawberry')
            ->case('pear', 'tasty')
            ->case('apple', 'delicious')
            ->case('banana', 'yellow')
            ->get();
    }

    public function testHandledFallback(): void
    {
        $handled = false;

        try {
            smatch('strawberry')
                ->case('pear', 'tasty')
                ->case('apple', 'delicious')
                ->case('banana', 'yellow')
                ->get();
        } catch (UnhandledSmatchException $e) {
            $handled = true;
            self::assertEquals('Unhandled smatch value of type string', $e->getMessage());
        }

        self::assertTrue($handled);
    }

    public function testNonIdentityChecksInteger(): void
    {
        $age = 23;

        $result = smatch(true)
            ->case($age >= 65, 'senior')
            ->case($age >= 25, 'adult')
            ->case($age >= 18, 'young adult')
            ->fallback('kid')
            ->get();

        self::assertEquals('young adult', $result);
    }

    public function testNonIdentityChecksString(): void
    {
        $text = 'Bienvenue chez nous';

        $result = smatch(true)
            ->case(self::str_contains($text, 'Welcome') || self::str_contains($text, 'Hello'), 'en')
            ->case(self::str_contains($text, 'Bienvenue') || self::str_contains($text, 'Bonjour'), 'fr')
            ->fallback('invalid')
            ->get();

        self::assertEquals('fr', $result);
    }

    public function testMatchOr(): void
    {
        $result = smatch('car')
            ->case('pear', 'tasty')
            ->case('apple', 'delicious')
            ->case('banana', 'yellow')
            ->getOr(function () {
                return 'complex logic';
            });

        self::assertEquals('complex logic', $result);
    }
}
