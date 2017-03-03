<?php

namespace MoneyManTests;

use MoneyMan\Currency;
use MoneyMan\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    /**
     * Currency Symbols for tests.
     * @var string(s)
     */
    const EURO_SYMBOL = "\xE2\x82\xAc";

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

    public function testMoneyCanAddAnotherMoneyObjectInTheSameCurrency()
    {
        // Test
        $amount1 = 352;
        $amount2 = 472;

        $currency1 = new Currency('USD');
        $currency2 = new Currency('USD');

        $money1 = new Money($amount1, $currency1);
        $money2 = new Money($amount2, $currency2);

        // Expected Result
        $expected_amount   = $amount1 + $amount2;
        $expected_currency = new Currency('USD');
        $expected_money    = new Money($expected_amount, $expected_currency);

        // Execute
        $new_money = $money1->add($money2);

        $this->assertEquals($expected_money, $new_money);
    }

    /**
     * @expectedException \MoneyMan\Exception\CannotAddDifferentCurrenciesException
     */
    public function testMoneyThrowsExceptionWhenTryingToAddTwoDifferentCurrencies()
    {
        // Test
        $amount1 = 352;
        $amount2 = 472;

        $currency1 = new Currency('USD');
        $currency2 = new Currency('EUR');

        $money1 = new Money($amount1, $currency1);
        $money2 = new Money($amount2, $currency2);

        // Expected Result
        $expected_amount   = $amount1 + $amount2;
        $expected_currency = new Currency('USD');
        $expected_money    = new Money($expected_amount, $expected_currency);

        // Execute
        $new_money = $money1->add($money2);

        $this->assertEquals($expected_money, $new_money);
    }

    public function moneyCanReturnCorrectFormatForDefaultLocaleDataProvider()
    {
        return [

            // #1 - Can Format Small Numbers Correctly w/ Default Locale
            [
                'test' => [
                    'amount'        => 232,
                    'currency_code' => 'USD',
                    'locale'        => 'en_US'
                ],
                'expected' => '$2.32'
            ],

            // #2 - Can Format Large Numbers Correctly w/ Default Locale
            [
                'test' => [
                    'amount'        => 1000000000,
                    'currency_code' => 'USD',
                    'locale'        => 'en_US'
                ],
                'expected' => '$10,000,000.00'
            ],

            // #3 - Can Format Less Than 1 Monetary Unit Correctly w/ Default Locale
            [
                'test' => [
                    'amount'        => 12,
                    'currency_code' => 'USD',
                    'locale'        => 'en_US'
                ],
                'expected' => '$0.12'
            ],

            // #4 - Can Format Small Numbers Correctly w/ Different Locale
            [
                'test' => [
                    'amount'        => 1245,
                    'currency_code' => 'USD',
                    'locale'        => 'de_DE' // Germany
                ],
                'expected' => '12,45 $'
            ],

            // #5 - Can Format Big Numbers Correctly w/ Different Locale
            [
                'test' => [
                    'amount'        => 135462452342,
                    'currency_code' => 'USD',
                    'locale'        => 'de_DE' // Germany
                ],
                'expected' => '1.354.624.523,42 $'
            ],

            // #6 - Can Format Less Than 1 Monetary Unit Correctly w/ Different Locale
            [
                'test' => [
                    'amount'        => 26,
                    'currency_code' => 'USD',
                    'locale'        => 'de_DE' // Germany
                ],
                'expected' => '0,26 $'
            ],

            // #6 - Test Random Locale Format 1
            [
                'test' => [
                    'amount'        => 23452,
                    'currency_code' => 'GBP',
                    'locale'        => 'ru_RU' // Russia
                ],
                'expected' => '234,52 £'
            ],

            // #7 - Test Random Locale Format 2
            [
                'test' => [
                    'amount'        => 634,
                    'currency_code' => 'EUR',
                    'locale'        => 'en_US'
                ],
                'expected' => self::EURO_SYMBOL . '6.34'
            ]
        ];
    }

    /**
     * @dataProvider moneyCanReturnCorrectFormatForDefaultLocaleDataProvider
     */
    public function testMoneyCanReturnCorrectFormatForDefaultLocale($test, $expected)
    {
        $amount   = $test['amount'];
        $currency = new Currency($test['currency_code']);
        $money    = new Money($amount, $currency);

        $this->assertEquals($expected, $money->getFormatted($test['locale']));
    }
}
