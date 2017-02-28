<?php

namespace MoneyMan;

use MoneyMan\Exception\InvalidCurrencyCodeException;

/**
 * The Currency Class
 *
 * This class is used to represent currencies as an entity.
 *
 * @package MoneyMan
 * @author  Graham A. Sutton <gsutton@sproutloud.com>
 */
class Currency
{
    /**
     * The 3-letter ISO code used to establish the currency
     * this object will represent.
     * @var string
     */
    private $code;

    /**
     * Constructor
     *
     * Establish the currency by setting the currency code. This defines
     * the currency this object represents.
     *
     * @param  string  $code
     *
     * @throws \MoneyMan\Exception\InvalidCurrencyCodeException
     */
    public function __construct($code)
    {
        if (!is_string($code) || strlen($code) > 3) {
            throw new InvalidCurrencyCodeException('Currency code should be a 3-letter string.  e.g. "USD"');
        }

        $this->code = $code;
    }

    /**
     * Get the currency code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Compares if another Currency is equal to this Currency.
     *
     * @param Currency $currency
     *
     * @return bool
     */
    public function equals(Currency $currency)
    {
        return $this->getCode() === $currency->getCode();
    }
}
