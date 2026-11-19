<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Measurement;
use PHPUnit\Framework\Attributes\DataProvider;

class MeasurementTest extends TestCase
{
    public static function dataGetFahrenheit(): array
    {
        return [
            ['0.5', '32.9'],
            ['-2.5', '27.5'],
            ['1.5', '34.7'],
            ['0.4', '32.72'],
            ['3.3', '37.94'],
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['50', '122'],
            ['-75', '-103'],
        ];
    }
    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $measurement = new Measurement();

        $measurement->setTemperature($celsius);
        $this->assertEquals(
            $expectedFahrenheit,
            $measurement->getFahrenheit(),
        );
    }
}


