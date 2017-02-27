<?php

namespace MoneyMan;

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
     */
    public function __construct($code)
    {
        if (!is_string($code)) {
            throw new \Exception('Currency code should be a string.');
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
}
