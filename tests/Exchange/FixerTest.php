<?php
/**
 * Created by PhpStorm.
 * User: graham1
 * Date: 2/27/17
 * Time: 20:18
 */

namespace MoneyManTests\Exchange;

use MoneyMan\Currency;
use MoneyMan\Exchange\Fixer;
use MoneyMan\Money;
use PHPUnit\Framework\TestCase;

class FixerTest extends TestCase
{
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

        // Mock HTTP Client Responses
        $results = [
            'base'  => $base_currency->getCode(),
            'date'  => '2017-02-27',
            'rates' => [
                $quote_currency->getCode() => $test['exchange_rate']
            ]
        ];

        $body = $this->getMockBuilder('\GuzzleHttp\Psr7\Stream')
            ->disableOriginalConstructor()
            ->getMock();

        $body->method('getContents')
            ->will($this->returnValue(json_encode($results)));

        $response = $this->getMockBuilder('\GuzzleHttp\Psr7\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $response->method('getBody')
            ->will($this->returnValue($body));

        $client = $this->getMockBuilder('\GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $client->method('request')
            ->will($this->returnValue($response));

        // Perform Exchange
        $exchange        = new Fixer($client);
        $converted_money = $exchange->exchange($money, $quote_currency);

        $this->assertEquals($expected_money, $converted_money);
    }
}