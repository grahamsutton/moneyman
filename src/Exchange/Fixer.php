<?php

namespace MoneyMan\Exchange;

use GuzzleHttp\Client;
use MoneyMan\Currency;
use MoneyMan\Exchange;

/**
 * The Fixer Exchange
 *
 * This exchange engine uses the fixer.io API to perform currency exchange.
 *
 * @package MoneyMan\Exchange
 * @author  Graham A. Sutton <gsutton@sproutloud.com>
 */
class Fixer extends Exchange
{
    /**
     * The HTTP client.
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Constructor
     *
     * Initializes the HTTP client needed to request the exchange rate from Fixer.io
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Gets the exchange rate between the base and quote currencies.
     *
     * @param Currency $base
     * @param Currency $quote
     *
     * @return float
     */
    public function getExchangeRate(Currency $base, Currency $quote)
    {
        $base_code  = $base->getCode();
        $quote_code = $quote->getCode();

        $response = $this->client->request(
            'GET',
            "https://api.fixer.io/latest?base=$base_code&symbols=$quote_code"
        );

        $response = json_decode($response->getBody()->getContents(), true);

        $exchange_rate = $response['rates'][$quote_code];

        return $exchange_rate;
    }
}