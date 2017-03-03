<?php
/**
 * Created by PhpStorm.
 * User: graham1
 * Date: 2/27/17
 * Time: 20:32
 */

namespace MoneyMan\Exception;

/**
 * Unsupported Exchange Service Provided Exception
 *
 * Should be thrown when an exchange services engine that is not
 * supported by MoneyMan is provided to be used as an exchange
 * engine.
 *
 * @package MoneyMan\Exception
 * @author  Graham A. Sutton <gsutton@sproutloud.com>
 */
class UnsupportedExchangeServiceProvidedException extends \Exception
{

}
