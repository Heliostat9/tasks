<?php

use Heliostat\Task1\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testResolveSimpleService(): void
    {
        $c = new Container();
        $c->register(Foo::class);
        $foo = $c->get(Foo::class);
        $this->assertInstanceOf(Foo::class, $foo);
    }

    public function testAutowireDependencies(): void
    {
        $c = new Container();
        $c->register(Bar::class);
        $c->register(FooWithBar::class);
        $foo = $c->get(FooWithBar::class);
        $this->assertInstanceOf(Bar::class, $foo->bar);
    }

    public function testAlias(): void
    {
        $c = new Container();
        $c->register(Bar::class);
        $c->alias(BarInterface::class, Bar::class);
        $bar = $c->get(BarInterface::class);
        $this->assertInstanceOf(Bar::class, $bar);
    }

    public function testSingleton(): void
    {
        $c = new Container();
        $c->singleton(Foo::class);
        $a = $c->get(Foo::class);
        $b = $c->get(Foo::class);
        $this->assertSame($a, $b);
    }
}

class Foo {}
class Bar implements BarInterface {}
interface BarInterface {}
class FooWithBar { public function __construct(public Bar $bar) {} }