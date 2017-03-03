<?php
/**
 * Created by PhpStorm.
 * User: graham1
 * Date: 3/1/17
 * Time: 21:31
 */

namespace MoneyMan;
use MoneyMan\Exception\UnsupportedExchangeServiceProvidedException;
use Swap\Swap;

/**
 * Service Factory Class
 *
 * The class is used to create the appropriate \Swap\Swap object
 * needed for the \MoneyMan\Exchange class.
 *
 * @package MoneyMan
 */
class ServiceFactory
{
    /**
     * List of usable exchange rate services.
     * @var string(s)
     */
    const FIXER  = 'fixer';
    const GOOGLE = 'google';
    const YAHOO  = 'yahoo';

    /**
     * A list of all usable exchange rate services.
     * @var array
     */
    private static $SERVICES = [
        self::FIXER,
        self::GOOGLE,
        self::YAHOO
    ];

    /**
     * Constructor
     *
     * This class cannot be instantiated.
     */
    private function __construct() {}

    /**
     * Create and return a \Swap\Swap object based on the provided
     * service name.
     *
     * @param string $service  The name of the service to use.
     *
     * @return \Swap\Swap
     */
    public static function getService($service)
    {
        switch ($service) {

            // Fixer
            case self::FIXER:
                return (new \Swap\Builder())
                    ->add('fixer')
                    ->build();

            // Google
            case self::GOOGLE:
                return (new \Swap\Builder())
                    ->add('google')
                    ->build();

            // Yahoo
            case self::YAHOO:
                return (new \Swap\Builder())
                    ->add('yahoo')
                    ->build();

            default:
                throw new UnsupportedExchangeServiceProvidedException(
                    "'$service' is not supported in MoneyMan." .
                    "Please use one of the following: " . implode(", ", self::$SERVICES)
                );
        }
    }
}