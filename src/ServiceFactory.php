<?php

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
    const YAHOO  = 'yahoo';

    /**
     * A list of all usable exchange rate services.
     * @var array
     */
    private static $SERVICES = [
        self::FIXER,
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