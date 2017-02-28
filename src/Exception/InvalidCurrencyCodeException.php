<?php
/**
 * Created by PhpStorm.
 * User: graham1
 * Date: 2/27/17
 * Time: 20:32
 */

namespace MoneyMan\Exception;

/**
 * Invalid Currency Code Exception Class
 *
 * Should be throw when an invalid currency code is provided
 * to a \MoneyMan\Currency object.
 *
 * @package MoneyMan\Exception
 * @author  Graham A. Sutton <gsutton@sproutloud.com>
 */
class InvalidCurrencyCodeException extends \Exception
{

}