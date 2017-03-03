# MoneyMan

[![CircleCI](https://circleci.com/gh/grahamsutton/moneyman.svg?style=svg)](https://circleci.com/gh/grahamsutton/moneyman)

MoneyMan is a way to represent money in your application as an object.

Using floating point numbers can be bad news and lead to a lot of headaches that may make you afraid to want to perform currency exchanges in your application. MoneyMan strives to make dealing with money easy by using the following:

* Immutable money objects that protect you from having money values *suddenly* become something else.
* Integer-based money values that protect your app against rounding errors.
* Currency exchange to convert money objects into a desired currency
* Interchangeable currency exchange services using either Google, Yahoo, or Fixer (backed by the Swap library)
* Formatting the display of your money object values to be shown in a desired locale
* Ability to two money objects of different currencies and get an output in desired currency

## Quick Example

```php
<?php

use MoneyMan\Money;
use MoneyMan\Currency;
use MoneyMan\Exchange;
use MoneyMan\ServiceFactory;

/**
 * Print value of a money object based on locale.
 */
$money = new Money(123400, new Currency('USD'));

// Get human readable value based on locale , default is 'en_US'
echo $money->getFormatted();  // "$1,234.00"

// Get human readable value based on specified locale
echo $money->getFormatted('de_DE');  // "1.234,00 $"


/**
 * Perform a simple exchange.
 */
$service = ServiceFactory::getService(ServiceFactory::GOOGLE);  // use Google Finance
$exchange = new Exchange($service);

$money = new Money(5000, new Currency('USD'));

// Pretend USD->EUR exchange rate is 0.92142
$exchanged_money = $exchange->exchange($money, new Currency('EUR'));

// Print the new money object value
echo $exchanged_money->getFormatted();  // "€46.07"

// Print it in different locale
echo $exchanged_money->getFormatted('de_DE');  // "46,07 €"


/**
 * Add two money objects in same currency
 */
$money1 = new Money(12300, new Currency('USD'));
$money2 = new Money(4500, new Currency('USD'));

// Returns a brand new Money object
$new_money = $money1->add($money2);

// Get human readable value of the new Money object
echo $new_money->getFormatted();  // "$16,800.00"


/**
 * Add two money objects in *different* currency
 */
// Get a service that will be used to perform the exchange
$service  = ServiceFactory::getService(ServiceFactory::YAHOO);  // will use Yahoo Finance
$exchange = new Exchange($service);

$money1 = new Money(4300, new Currency('USD'));
$money2 = new Money(6700, new Currency('EUR'));

// $money1 will be converted to $money2's currency and then added together.
// Pretend USD->EUR exchange rate is 0.89423
$exchanged_money = $exchange->add($money1, $money2);

// Print the new money object's value
echo $exchanged_money->getFormatted();  // "€105.45"

// Print the new money object in different locale
echo $exchanged_money->getFormatted('de_DE');  // "105,45 €"
```

## Why *Immutable* Money Objects?

It may not seem to make a whole bunch of sense at first, but when you really think a little deeper, it does. Imagine you have a $20 USD bill. Can a $20 bill *suddenly* become $35? Obviously, no. You must *add* $15 to it to get a *new* single value, you do not change the value of the $20 bill.

Money objects in MoneyMan intend to take a more "natural" world approach. In the example, the $20 bill represents a single value of money (the firs object). When we add it $15, we are adding a second, separate value of money (the second object). From this we get a new, single value of both totals combined (a new, third object).
