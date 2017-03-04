<?php

namespace MoneyMan;

use MoneyMan\Currency;
use MoneyMan\Money;
use Swap\Swap;

/**
 * Exchange Class
 *
 * Defines how an exchange should be performed. A subclass is needed to derive
 * the exchange rate used for
 *
 * @category Exchange
 * @package  Exchange
 * @author   Graham A. Sutton <gsutton@sproutloud.com>
 * @license  MIT <http://mit.org>
 * @link     http://mit.org
 */
class Exchange
{
    /**
     * The exchange rate service.
     * @var \Swap\Swap
     */
    private $service;

    /**
     * Constructor
     *
     * Accepts the name of an exchange rates service from Swap. Default is set to
     * use Fixer.
     *
     * @param \Swap\Swap $service  The service to query exchange rates from.
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * Add two \MoneyMan\Money objects together. This will return a new \MoneyMan\Money
     * object.
     *
     * IMPORTANT! If the currencies are different, the first parameter will be treated
     * as the base currency while the second parameter will be the quote currency, so
     * you will end up getting back a \MoneyMan\Money object in the second parameter's
     * currency with both of those objects' amounts added together.
     *
     * @param \MoneyMan\Money $money1  The first money object.
     * @param \MoneyMan\Money $money2  The second money object.
     *
     * @return \MoneyMan\Money
     */
    public function add(Money $money1, Money $money2)
    {
        // Convert the base to the quote currency
        $converted_money = $this->exchange($money1, $money2->getCurrency());

        // Return the new \MoneyMan\Money in the quote currency
        return $converted_money->add($money2);
    }

    /**
     * Exchanges one \MoneyMan\Money object into the desired currency.
     *
     * This should return a brand new \MoneyMan\Money object.
     *
     * @param \MoneyMan\Money    $base   The money we are exchanging.
     * @param \MoneyMan\Currency $quote  The currency we are exchanging to.
     *
     * @return \MoneyMan\Money
     */
    public function exchange(Money $base, Currency $quote)
    {
        // If they are of the same currency, there's no need to query the service.
        $exchange_rate = $base->getCurrency()->equals($quote)
            ? 1.00000
            : $this->getExchangeRate($base->getCurrency(), $quote);

        return new Money(
            (int) round($base->getAmount() * $exchange_rate, 0),
            $quote
        );
    }

    /**
     * Gets the exchange rate between the base and quote currencies.
     *
     * @param Currency $base   The currency we want to exchange from.
     * @param Currency $quote  The currency we want to exchange to.
     *
     * @return float
     */
    public function getExchangeRate(Currency $base, Currency $quote)
    {
        $base_code  = $base->getCode();
        $quote_code = $quote->getCode();

        return $this->service->latest("$base_code/$quote_code")->getValue();
    }
}
