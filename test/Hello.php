<?php
declare(strict_types=1);

namespace DD\test;

use PHPUnit\Framework\TestCase;
use DD\SOAP as DDSoap;

final class Hello extends TestCase {
    public function sayHello(): string {
        $u = '';
        $k = 'Key_fe239e5985321499ae656b5139d11ce1';
        $con = DDSoap::getConection($u, $k);
        return $con->do('wellcome',[]);
    }

    public function doIHaveStamps() :\StdClass
    {
        $u = '';
        $k = 'Key_fe239e5985321499ae656b5139d11ce1';
        $con = DDSoap::getConection($u, $k);
        return $con->do('timbresDisponibles',[]);
    }

    public function testCanBeCreatedFromValidEmailAddress(): void
    {
        $this->assertInstanceOf(
            Email::class,
            Email::fromString('user@example.com')
        );
    }

    public function testCannotBeCreatedFromInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString('invalid');
    }

    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            'user@example.com',
            Email::fromString('user@example.com')
        );
    }
}