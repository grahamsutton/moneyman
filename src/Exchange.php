<?php

namespace MoneyMan;

use MoneyMan\Currency;
use MoneyMan\Money;

/**
 * Exchange Class <<abstract>>
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
abstract class Exchange
{
    /**
     * List of exchange rates that have already been retrieved.
     * @var array
     */
    protected $exchange_rates;

    /**
     * Exchanges one \MoneyMan\Money object into the desired currency.
     *
     * This should return a brand new \MoneyMan\Money object.
     *
     * @param \MoneyMan\Money    $base  The money we are exchanging.
     * @param \MoneyMan\Currency $quote The currency we are exchanging to.
     *
     * @return \MoneyMan\Money
     */
    public function exchange(Money $base, Currency $quote)
    {
        // Key is a concatenated currency pairing, e.g. 'USDGBP'
        $key = $base->getCurrency()->getCode() . $quote->getCode();

        if ($base->getCurrency()->equals($quote)) {
            $this->exchange_rates[$key] = 1;
        }

        // Get previously retrieved exchange rate, otherwise fetch it from the data source.
        if (isset($this->exchange_rates[$key])) {
            $exchange_rate = $this->exchange_rates[$key];
        } else {
            $exchange_rate = $this->getExchangeRate($base->getCurrency(), $quote);
        }

        return new Money(
            (int) round($base->getAmount() * $exchange_rate, 0),
            $quote
        );
    }

    /**
     * Get exchange rate between two different currencies.
     *
     * @param \MoneyMan\Currency $base  The currency we want to exchange from.
     * @param \MoneyMan\Currency $quote The currency we want to exchange to.
     *
     * @return float
     */
    abstract public function getExchangeRate(Currency $base, Currency $quote);
}
