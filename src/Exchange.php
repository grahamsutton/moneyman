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
     * IMPORTANT! If the currencies are different, the second parameter will be converted
     * to the first parameter's currency and then added together.
     *
     * @param \MoneyMan\Money $money1  The first money object.
     * @param \MoneyMan\Money $money2  The second money object.
     *
     * @return \MoneyMan\Money
     */
    public function add(Money $money1, Money $money2)
    {
        // Convert the second money object to the first money object's currency
        $converted_money = $this->exchange($money2, $money1->getCurrency());

        // Return the new \MoneyMan\Money in the first parameter's currency
        return $money1->add($converted_money);
    }

    /**
     * Subtract one \MoneyMan\Money object from another. This will return a brand new
     * \MoneyMan\Money object.
     *
     * IMPORTANT! If the currencies are different, the second parameter will be converted
     * to the first parameter's currency. Afterwards, the second parameter's exchanged
     * amount will be deducted from the first parameter's amount and returned in a
     * new \MoneyMan\Money object.
     *
     * @param \MoneyMan\Money $money1  The first money object
     * @param \MoneyMan\Money $money2  The second money object
     *
     * @return \MoneyMan\Money
     */
    public function subtract(Money $money1, Money $money2)
    {
        // Convert the second money object to the first money object's currency
        $converted_money = $this->exchange($money2, $money1->getCurrency());

        // Return the new \MoneyMan\Money in the first parameter's currency
        return $money1->subtract($converted_money);
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
