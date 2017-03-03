<?php

namespace MoneyManTests\Exchange;

use MoneyMan\Currency;
use MoneyMan\Exchange;
use MoneyMan\Money;
use MoneyMan\ServiceFactory;
use PHPUnit\Framework\TestCase;

class ExchangeTest extends TestCase
{
    /*-------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------
    |
    | These helper methods are used to quickly generate mock objects used
    | with the \MoneyMan\Exchange class. The aim is to reduce clutter
    | in tests so they can be easily deciphered.
    |
    */

    /**
     * Get a \Swap\Swap mock object.
     *
     * You will need to provide an exchange rate in case there are any
     * exchanges happening.
     *
     * This object is returned from methods in \Swap\Swap like latest().
     *
     * @param float $exchange_rate_value  The value for the one-time exchange.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSwapServiceMock($exchange_rate_value)
    {
        // \Exchanger\ExchangeRate is declared as final, so we unfortunately cannot mock it
        $exchange_rate = new \Exchanger\ExchangeRate($exchange_rate_value);

        $swap_service = $this->getMockBuilder('\Swap\Swap')
            ->disableOriginalConstructor()
            ->getMock();

        $swap_service->method('latest')
            ->will($this->returnValue($exchange_rate));

        return $swap_service;
    }

    /*-------------------------------------------------------------------
    | Tests
    |--------------------------------------------------------------------
    |
    | Everything from here and down are the actual tests.
    |
    */

    public function exchangeProperlyExchangesAmountToNewCurrencyDataProvider()
    {
        return [

            // #1
            [
                'test' => [
                    'exchange_rate'  => 0.83823,
                    'base_amount'    => 1250,
                    'base_currency'  => 'COP',
                    'quote_currency' => 'GBP'
                ],
                'expected' => [
                    'amount'   => 1048,
                    'currency' => 'GBP'
                ]
            ],

            // #2
            [
                'test' => [
                    'exchange_rate'  => 1.72323,
                    'base_amount'    => 232452,
                    'base_currency'  => 'GBP',
                    'quote_currency' => 'EUR'
                ],
                'expected' => [
                    'amount'   => 400568,
                    'currency' => 'EUR'
                ]
            ],

            // #3
            [
                'test' => [
                    'exchange_rate'  => 3.12342,
                    'base_amount'    => 12352,
                    'base_currency'  => 'EUR',
                    'quote_currency' => 'USD'
                ],
                'expected' => [
                    'amount'   => 38580,
                    'currency' => 'USD'
                ]
            ],

            // #4
            [
                'test' => [
                    'exchange_rate'  => 0.84982,
                    'base_amount'    => 34,
                    'base_currency'  => 'AUD',
                    'quote_currency' => 'GBP'
                ],
                'expected' => [
                    'amount'   => 29,
                    'currency' => 'GBP'
                ]
            ]
        ];
    }

    /**
     * @dataProvider exchangeProperlyExchangesAmountToNewCurrencyDataProvider
     */
    public function testExchangeProperlyExchangesAmountToNewCurrency($test, $expected)
    {
        // Expected Result
        $expected_amount   = $expected['amount'];
        $expected_currency = new Currency($expected['currency']);
        $expected_money    = new Money($expected_amount, $expected_currency);

        // Test
        $amount         = $test['base_amount'];
        $base_currency  = new Currency($test['base_currency']);
        $quote_currency = new Currency($test['quote_currency']);
        $money          = new Money($amount, $base_currency);

        $service = $this->getSwapServiceMock($test['exchange_rate']);

        // Perform Exchange
        $exchange        = new Exchange($service);
        $converted_money = $exchange->exchange($money, $quote_currency);

        $this->assertEquals($expected_money, $converted_money);
    }

    public function testAddingTwoMoneyObjectsWithDifferentCurrenciesReturnsWithCorrectAmount()
    {
        $usd_money  = new Money(467, new Currency('USD'));
        $euro_money = new Money(1235, new Currency('EUR'));

        $service = $this->getSwapServiceMock(0.82348);

        $exchange       = new Exchange($service);
        $combined_money = $exchange->add($usd_money, $euro_money);

        $this->assertEquals(
            new Money(1620, new Currency('EUR')),
            $combined_money
        );
    }
}