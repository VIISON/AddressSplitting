# pickware/address-splitter

[![Packagist](https://img.shields.io/packagist/v/viison/address-splitter.svg?style=flat-square)](https://packagist.org/packages/viison/address-splitter) [![Build Status](https://img.shields.io/travis/VIISON/AddressSplitting.svg?style=flat-square)](https://travis-ci.org/VIISON/AddressSplitting) [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Tries to split an address line into street name, house number and other additional information like building,
apartment information etc.

## Installation

This project can be installed via [Composer](https://getcomposer.org/):

``` bash
$ composer require pickware/address-splitter
```

## Usage
The AddressSplittingService only contains one static function `splitAddress` that performs the address splitting.
The function expects one parameter, which is the address line. The function returns an associative array with the
keys `additionToAddress1`, `streetName`, `houseNumber` and `additionToAddress2`. `additionToAddress1` and `additionToAddress2`
contain information given in front of or after the street name & house number, respectively.

## Example
You can use the address splitting service as follows:

```php
var_dump(\Pickware\AddressSplitter\AddressSplitter::splitAddress('Pallaswiesenstr. 57B App. 235'));
```

The output of this command will be:

```php
array(4) {
	["additionToAddress1"]=>
	string(0) ""
	["streetName"]=>
	string(16) "Pallaswiesenstr."
	["houseNumber"]=>
	string(2) "57B"
	["houseNumberParts"]=> array(2) {
	    ["base"]=>
	    string(2) "57"
	    ["extension"]=>
	    string(1) "B"    
	}
	["additionToAddress2"]=>
	string(8) "App. 235"
}
```

## Supported Address Formats
We try to support all address formats used world-wide. E.g., the address splitter will work no matter if the house number
is given in front of or after the street name.

Here is a number of examples of addresses and how their splitted representation looks like:

| Address line                                 | additionToAddress1 |  streetName              | houseNumber | base | extension | additionToAddress2 |
|----------------------------------------------|--------------------|--------------------------|-------------|------|-----------|--------------------|
| 56, route de Genève                          |                    | route de Genève          | 56          | 56   |           |                    |
| Piazza dell'Indipendenza 14                  |                    | Piazza dell'Indipendenza | 14          | 14   |           |                    |
| Neuhof 13/15                                 |                    | Neuhof                   | 13/15       | 13   | 15        |                    |
| 574 E 10th Street                            |                    | E 10th Street            | 574         | 574  |           |                    |
| 1101 Madison St # 600                        |                    | Madison St               | 1101        | 1101 |           | # 600              |
| 3940 Radio Road, Unit 110                    |                    | Radio Road               | 3940        | 3940 |           | Unit 110           |
| D 6, 2                                       |                    | D 6                      | 2           | 2    |           |                    |
| 13 2ème Avenue                               |                    | 2ème Avenue              | 13          | 13   |           |                    |
| Apenrader Str. 16 / Whg. 3                   |                    | Apenrader Str.           | 16          | 16   |           | Whg. 3             |
| Pallaswiesenstr. 57 App. 235                 |                    | Pallaswiesenstr.         | 57          | 57   |           | App. 235           |
| Kirchengasse 7, 1. Stock Zi.Nr. 4            |                    | Kirchengasse             | 7           | 7    |           | 1. Stock Zi.Nr. 4  |
| Wiesentcenter, Bayreuther Str. 108, 2. Stock | Wiesentcenter      | Bayreuther Str.          | 108         | 108  |           | 2. Stock           |
| 244W 300N #101                               |                    | W 300N                   | 244         | 244  |           | #101               |
| Corso XXII Marzo 69                          |                    | Corso XXII Marzo         | 69          | 69   |           |                    |
| Frauenplatz 14 A                             |                    | Frauenplatz              | 14 A        | 14   | A         |                    |
| Mannerheimintie 13A2                         |                    | Mannerheimintie          | 13A2        | 13   | A2        |                    |
| Kerkstraat 13-HS                             |                    | Kerkstraat               | 13-HS       | 13   | HS        |                    |
| Poststr. 15-WG2                              |                    | Poststr.                 | 15-WG2      | 15   |  WG2      |                    |
| Hollandweg1A                                 |                    | Hollandweg               | 1A          | 1    | A         |                    |
| Poststr. 2 1/2                               |                    | Poststr.                 | 2 1/2       | 2    | 1/2       |                    |
| Breitenstr. 13/15/8/6                        |                    | Breitenstr.              | 13/15/8/6   | 13   | 13/15/8/6 |                    |
| Österreicher Weg 12A/8/6                     |                    | Österreicher Weg         | 12A/8/6     | 12   | A/8/6     |                    |
| Schegargasse 13-15/8/6                       |                    | Schegargasse             | 13-15/8/6   | 13   | 15/8/6    |                    |

## Unit Tests

You can run the tests via [PHPUnit](https://phpunit.de/) from the command line:

```
$ phpunit
```

If you don't have PHPUnit installed globally, run `composer install` first.

## License ##

**pickware/address-splitter** is licensed under the MIT license.  See the `LICENSE` file for more details.


