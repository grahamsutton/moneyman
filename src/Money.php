<?php

namespace MoneyMan;

use MoneyMan\Currency;
use MoneyMan\Exception\AmountIsNotAnIntegerException;

/**
 * The Money Class
 *
 * This class represents a money entity which consists
 * of an amount and a currency code.
 *
 * @author Graham A. Sutton <gsutton@sproutloud.com>
 */
class Money
{
    /**
     * The amount of the monetary unit.
     * @var int
     */
    private $amount;

    /**
     * The currency of the monetary unit.
     * @var \MoneyMan\Currency
     */
    private $currency;

    /**
     * Constructor
     *
     * Sets the immutable amount and currency.
     *
     * @param  int                 $amount
     * @param  \MoneyMan\Currency  $currency
     */
    public function __construct($amount, Currency $currency)
    {
        if (!is_int($amount)) {
            throw new AmountIsNotAnIntegerException(
                'Cannot set a non-integer value as an amount on a \MoneyMan\Money object.'
            );
        }

        $this->amount   = $amount;
        $this->currency = $currency;
    }

    /**
     * Get the currency of the monetary unit.
     *
     * @return \MoneyMan\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get the amount of the monetary unit.
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get the amount and currency code in a human
     * readable format.
     *
     * e.g. (new \MoneyMan\Money(800, 'USD'))->getFormatted(); // => "$8.00"
     *
     * @return string
     */
    public function getFormatted()
    {

    }
}
