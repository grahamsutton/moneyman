<?php

namespace MoneyMan;

use MoneyMan\Exception\AmountIsNotAnIntegerException;
use MoneyMan\Exception\CannotAddDifferentCurrenciesException;
use MoneyMan\Exception\CannotSubtractDifferentCurrenciesException;

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
     * e.g. (new \MoneyMan\Money(800, new \MoneyMan\Currency('USD')))->getFormatted(); // => "$8.00"
     *
     * @param string $locale  The locale to format the output to.
     *
     * @return string
     */
    public function getFormatted($locale = 'en_US')
    {
        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);

        // Divide by 100 since formatCurrency accepts a float as first argument
        $money_string = $formatter->formatCurrency(
            $this->getAmount() / 100,
            $this->getCurrency()->getCode()
        );

        // Replace invisible characters with a space.
        return str_replace("\xC2\xA0", " ", $money_string);
    }

    /**
     * Adds two \MoneyMan\Money objects together by combining the amounts and
     * returning a new \MoneyMan\Money object.
     *
     * This method can only add two money objects of the same \MoneyMan\Currency
     * type.
     *
     * @param \MoneyMan\Money $money
     *
     * @return \MoneyMan\Money
     *
     * @throws \MoneyMan\Exception\CannotAddDifferentCurrenciesException
     */
    public function add(Money $money)
    {
        // Validate they are of the same currency.
        if ($this->getCurrency()->getCode() !== $money->getCurrency()->getCode()) {
            throw new CannotAddDifferentCurrenciesException(
                'To directly add two money objects together, they must be of the same currency.' .
                'Use \MoneyMan\Exchange::add(\MoneyMan\Money, \MoneyMan\Money) to add \MoneyMan\Money ' .
                'objects of different currencies.'
            );
        }

        $total_amount = $this->getAmount() + $money->getAmount();

        return new self(
            $total_amount,
            $this->getCurrency()
        );
    }

    /**
     * Subtracts the incoming \MoneyMan\Money object's amount from this object.
     *
     * This method can only add two money objects of the same \MoneyMan\Currency
     * type.
     *
     * @param \MoneyMan\Money $money
     *
     * @return \MoneyMan\Money
     *
     * @throws \MoneyMan\Exception\CannotAddDifferentCurrenciesException
     */
    public function subtract(Money $money)
    {
        // Validate they are of the same currency.
        if ($this->getCurrency()->getCode() !== $money->getCurrency()->getCode()) {
            throw new CannotSubtractDifferentCurrenciesException(
                'To directly subtract one money object from another, they must be of the same currency.' .
                'Use \MoneyMan\Exchange::subtract(\MoneyMan\Money, \MoneyMan\Money) to subtract \MoneyMan\Money ' .
                'objects of different currencies.'
            );
        }

        $total_amount = $this->getAmount() - $money->getAmount();

        return new self(
            $total_amount,
            $this->getCurrency()
        );
    }
}
