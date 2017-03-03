<?php
/**
 * Created by PhpStorm.
 * User: graham1
 * Date: 2/27/17
 * Time: 20:32
 */

namespace MoneyMan\Exception;

/**
 * Cannot Add Different Currencies Exception Class
 *
 * Should be throw when two \MoneyMan\Money objects with different
 * \MoneyMan\Currency objects is attempted when it should not be
 * possible.
 *
 * @package MoneyMan\Exception
 * @author  Graham A. Sutton <gsutton@sproutloud.com>
 */
class CannotAddDifferentCurrenciesException extends \Exception
{

}