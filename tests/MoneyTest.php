<?php

namespace MoneyManTests;

use MoneyMan\Currency;
use MoneyMan\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function testMoneyCanCorrectlySetAmount()
    {
        $amount   = 800;
        $currency = new Currency('USD');
        $money    = new Money($amount, $currency);

        $this->assertEquals($amount, $money->getAmount());
    }

    public function testMoneyCanCorrectlySetCurrency()
    {
        $amount   = 700;
        $currency = new Currency('EUR');
        $money    = new Money($amount, $currency);

        $this->assertEquals($currency, $money->getCurrency());
    }

    /**
     * @expectedException \MoneyMan\Exception\AmountIsNotAnIntegerException
     */
    public function testMoneyDoesNotAllowANonIntegerAsAnAmount()
    {
        $amount   = 5.00;
        $currency = new Currency('COP');
        $money    = new Money($amount, $currency);
    }

    /**
     * @expectedException TypeError
     */
    public function testsMoneyDoesNotAllowNonCurrencyObject()
    {
        $amount   = 600;
        $currency = 'this is not right';
        $money    = new Money($amount, $currency);
    }
}
