<?php

namespace MoneyManTests;

use MoneyMan\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function testCurrencyCodeGetsProperlySet()
    {
        $currency = new Currency('USD');
        $this->assertEquals('USD', $currency->getCode());
    }

    /**
     * @expectedException \MoneyMan\Exception\InvalidCurrencyCodeException
     */
    public function testGreaterThanThreeLetterIsoCodeThrowsInvalidCurrencyCodeException()
    {
        $currency = new Currency('thisIsWrong');
    }

    /**
     * @expectedException \MoneyMan\Exception\InvalidCurrencyCodeException
     */
    public function testNonStringThrowsInvalidCurrencyCodeException()
    {
        $currency = new Currency(123);
    }

    public function testComparingTwoEqualCurrenciesReturnsTrue()
    {
        $currency1 = new Currency('USD');
        $currency2 = new Currency('USD');

        $this->assertTrue($currency1->equals($currency2));
    }

    public function testComparingTwoUnequalCurrenciesReturnsFalse()
    {
        $currency1 = new Currency('USD');
        $currency2 = new Currency('EUR');

        $this->assertFalse($currency1->equals($currency2));
    }
}
