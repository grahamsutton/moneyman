<?php

namespace MoneyMan;

use MoneyMan\Currency;
use MoneyMan\Money;

/**
 * Exchange Interface
 *
 * Defines the contract needed to establish future currency exchange
 * engines from different providers.
 *
 * @category Exchange
 * @package  Exchange
 * @author   Graham A. Sutton <gsutton@sproutloud.com>
 * @license  MIT <http://mit.org>
 * @link     http://mit.org
 */
interface Exchange
{
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
    public function exchange(Money $base, Currency $quote);

    /**
     * Get exchange rate between two different currencies.
     *
     * @param \MoneyMan\Currency $base  The currency we want to exchange from.
     * @param \MoneyMan\Currency $quote The currency we want to exchange to.
     *
     * @return float
     */
    public function getExchangeRate(Currency $base, Currency $quote);
}
