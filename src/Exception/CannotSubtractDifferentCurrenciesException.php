<?php

namespace MoneyMan\Exception;

/**
 * Cannot Subtract Different Currencies Exception Class
 *
 * Should be thrown when two \MoneyMan\Money objects with different
 * \MoneyMan\Currency objects is attempted to be calculated when
 * it should not be possible.
 *
 * @package MoneyMan\Exception
 * @author  Graham A. Sutton <gsutton@sproutloud.com>
 */
class CannotSubtractDifferentCurrenciesException extends \Exception
{

}