<?php

namespace MoneyMan\Exception;

/**
 * Amount Is Not An Integer Exception
 *
 * This exception should be thrown in cases where a \MoneyMan\Money
 * object attempts to set a non-integer value.
 *
 * @author Graham A. Sutton <gsutton@sproutloud.com> 
 */
class AmountIsNotAnIntegerException extends \Exception
{

}