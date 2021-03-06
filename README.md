# MoneyMan

[![Build Status](https://scrutinizer-ci.com/g/grahamsutton/moneyman/badges/build.png?b=master)](https://scrutinizer-ci.com/g/grahamsutton/moneyman/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/grahamsutton/moneyman/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/grahamsutton/moneyman/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/grahamsutton/moneyman/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/grahamsutton/moneyman/?branch=master)


MoneyMan is a way to represent money in your application as an object.


Using floating point numbers can be bad news and lead to a lot of headaches that may make you afraid to want to perform currency exchanges in your application. MoneyMan strives to make dealing with money easy by using the following:

* Immutable money objects that protect you from having money values *suddenly* become something else.
* Integer-based money values that protect your app against rounding errors.
* Currency exchange to convert money objects into a desired currency
* Interchangeable currency exchange services using either Google, Yahoo, or Fixer (backed by the Swap library)
* Formatting the display of your money object values to be shown in a desired locale
* Ability to two money objects of different currencies and get an output in desired currency

Currently supported exchange rate servcies include:

* [Fixer](http://fixer.io/)
* [Yahoo Finance](https://finance.yahoo.com)

**Install**

Use the command line:
```bash
$ composer require grahamsutton/moneyman
```

## Quick Example

**Create money object and print its value based on locale**
```php
use MoneyMan\Money;
use MoneyMan\Currency;

$money = new Money(123400, new Currency('USD'));

// Get human readable value based on locale , default is 'en_US'
echo $money->getFormatted();  // "$1,234.00"

// Get human readable value based on specified locale
echo $money->getFormatted('de_DE');  // "1.234,00 $"
```

**Get a Swap object for fetching rates**
```php
use MoneyMan\Exchange;
use MoneyMan\ServiceFactory;

// Available services
$yahoo_service = ServiceFactory::getService('yahoo');
$fixer_service = ServiceFactory::getService('fixer');

// Pass to a \MoneyMan\Exchange object
$exchange = new Exchange($fixer_service);
```

**Exchange a Money object from one currency to another**
```php
use MoneyMan\Money;
use MoneyMan\Currency;
use MoneyMan\Exchange;
use MoneyMan\ServiceFactory;

$service = ServiceFactory::getService('fixer');  // use Fixer.io
$exchange = new Exchange($service);

$money = new Money(5000, new Currency('USD'));

// Pretend USD->EUR exchange rate is 0.92142
$exchanged_money = $exchange->exchange($money, new Currency('EUR'));

// Print the new money object value
echo $exchanged_money->getFormatted();  // "€46.07"

// Print it in different locale
echo $exchanged_money->getFormatted('de_DE');  // "46,07 €"
```

### Doing Arithmetic

**Add two Money objects with same currency**
```php
use MoneyMan\Money;
use MoneyMan\Currency;

$money1 = new Money(12300, new Currency('USD'));
$money2 = new Money(4500, new Currency('USD'));

// Returns a brand new Money object
$new_money = $money1->add($money2);

// Get human readable value of the new Money object
echo $new_money->getFormatted();  // "$168.00"
```

**Subtract two Money objects with same currency**
```php
use MoneyMan\Money;
use MoneyMan\Currency;

$money1 = new Money(12300, new Currency('USD'));
$money2 = new Money(4500, new Currency('USD'));

// Returns a brand new Money object
$new_money = $money1->subtract($money2);  // think $money1 - $money2

// Get human readable value of the new Money object
echo $new_money->getFormatted();  // "$78.00"
```

**Add two Money objects with different currencies**
```php
use MoneyMan\Money;
use MoneyMan\Currency;
use MoneyMan\Exchange;
use MoneyMan\ServiceFactory;

// Get a service that will be used to perform the exchange
$service  = ServiceFactory::getService('yahoo');  // will use Yahoo Finance
$exchange = new Exchange($service);

$money1 = new Money(4300, new Currency('USD'));
$money2 = new Money(6700, new Currency('EUR'));

// $money2 will be converted to $money1's currency and then added together.
// Pretend EUR->USD exchange rate is 1.09124
$exchanged_money = $exchange->add($money1, $money2);

// Print the new money object's value
echo $exchanged_money->getFormatted();  // "$116.11"

// Print the new money object in different locale
echo $exchanged_money->getFormatted('de_DE');  // "116,11 $"
```

**Subtract two Money objects with different currencies**
```php
...

$money1 = new Money(10000, new Currency('EUR'));
$money2 = new Money(5300, new Currency('USD'));

// $money2 will be converted to $money1's currency and then subtracted from $money1
// Pretend USD->EUR exchange rate is 0.94235
$exchanged_money = $exchange->subtract($money1, $money2);  // think $money1 - $money2

// Print the new money object's value
echo $exchanged_money->getFormatted();  // "€50.05"

// Print the new money object in different locale
echo $exchanged_money->getFormatted('de_DE');  // "50,05 €"
```

## Why *Immutable* Money Objects?

It may not seem to make a whole bunch of sense at first, but when you really think a little deeper, it does. Imagine you have a $20 USD bill. Can a $20 bill *suddenly* become $35? Obviously, no. You must *add* $15 to it to get a *new* single value, you do not change the value of the $20 bill.

Money objects in MoneyMan intend to take a more "natural" world approach. In the example, the $20 bill represents a single value of money (the first Money object). When we add $15 to it, we are adding a second, separate value of money (the second Money object). From this we get a new, single value of both totals combined (a new, third Money object).

## MIT License

The MIT License

Copyright (c) 2017 Graham Sutton

Permission is hereby granted, free of charge, 
to any person obtaining a copy of this software and 
associated documentation files (the "Software"), to 
deal in the Software without restriction, including 
without limitation the rights to use, copy, modify, 
merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom 
the Software is furnished to do so, 
subject to the following conditions:

The above copyright notice and this permission notice 
shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES 
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR 
ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, 
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
