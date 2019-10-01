<?php
namespace Pickware\AddressSplitter\Test;

use PHPUnit_Framework_TestCase;
use Pickware\AddressSplitter\AddressSplitter;

class AddressSplitterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validAddressesProvider
     *
     * @param string $address
     * @param array $expected
     */
    public function testValidAddresses($address, $expected)
    {
        static::assertSame($expected, AddressSplitter::splitAddress($address));
    }

    /**
     * @return array
     */
    public function validAddressesProvider()
    {
        return [
            [
                '56, route de Genève',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'route de Genève',
                    'houseNumber' => '56',
                    'houseNumberParts' => [
                        'base' => '56',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Piazza dell\'Indipendenza 14',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Piazza dell\'Indipendenza',
                    'houseNumber' => '14',
                    'houseNumberParts' => [
                        'base' => '14',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Neuhof 13/15',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Neuhof',
                    'houseNumber' => '13/15',
                    'houseNumberParts' => [
                        'base' => '13',
                        'extension' => '15',
                    ],
                    'additionToAddress2' => '',

                ],
            ],
            [
                '574 E 10th Street',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'E 10th Street',
                    'houseNumber' => '574',
                    'houseNumberParts' => [
                        'base' => '574',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                '1101 Madison St # 600',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Madison St',
                    'houseNumber' => '1101',
                    'houseNumberParts' => [
                        'base' => '1101',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '# 600',
                ],
            ],
            [
                '3940 Radio Road, Unit 110',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Radio Road',
                    'houseNumber' => '3940',
                    'houseNumberParts' => [
                        'base' => '3940',
                        'extension' => '',
                    ],
                    'additionToAddress2' => 'Unit 110',
                ],
            ],
            [
                'D 6, 2',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'D 6',
                    'houseNumber' => '2',
                    'houseNumberParts' => [
                        'base' => '2',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                '13 2ème Avenue',
                [
                    'additionToAddress1' => '',
                    'streetName' => '2ème Avenue',
                    'houseNumber' => '13',
                    'houseNumberParts' => [
                        'base' => '13',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                '13 2ème Avenue, App 3',
                [
                    'additionToAddress1' => '',
                    'streetName' => '2ème Avenue',
                    'houseNumber' => '13',
                    'houseNumberParts' => [
                        'base' => '13',
                        'extension' => '',
                    ],
                    'additionToAddress2' => 'App 3',
                ],
            ],
            [
                'Apenrader Str. 16 / Whg. 3',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Apenrader Str.',
                    'houseNumber' => '16',
                    'houseNumberParts' => [
                        'base' => '16',
                        'extension' => '',
                    ],
                    'additionToAddress2' => 'Whg. 3',
                ],
            ],
            [
                'Pallaswiesenstr. 57 App. 235',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Pallaswiesenstr.',
                    'houseNumber' => '57',
                    'houseNumberParts' => [
                        'base' => '57',
                        'extension' => '',
                    ],
                    'additionToAddress2' => 'App. 235',
                ],
            ],
            [
                'Kirchengasse 7, 1. Stock Zi.Nr. 4',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Kirchengasse',
                    'houseNumber' => '7',
                    'houseNumberParts' => [
                        'base' => '7',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '1. Stock Zi.Nr. 4',
                ],
            ],
            [
                'Wiesentcenter, Bayreuther Str. 108, 2. Stock',
                [
                    'additionToAddress1' => 'Wiesentcenter',
                    'streetName' => 'Bayreuther Str.',
                    'houseNumber' => '108',
                    'houseNumberParts' => [
                        'base' => '108',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '2. Stock',
                ],
            ],
            [
                '244W 300N #101',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'W 300N',
                    'houseNumber' => '244',
                    'houseNumberParts' => [
                        'base' => '244',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '#101',
                ],
            ],
            [
                'Corso XXII Marzo 69',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Corso XXII Marzo',
                    'houseNumber' => '69',
                    'houseNumberParts' => [
                        'base' => '69',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Frauenplatz 14 A',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Frauenplatz',
                    'houseNumber' => '14 A',
                    'houseNumberParts' => [
                        'base' => '14',
                        'extension' => 'A',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Mannerheimintie 13A2',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Mannerheimintie',
                    'houseNumber' => '13A2',
                    'houseNumberParts' => [
                        'base' => '13',
                        'extension' => 'A2',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Heinestr.13',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Heinestr.',
                    'houseNumber' => '13',
                    'houseNumberParts' => [
                        'base' => '13',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Am Aubach11',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Am Aubach',
                    'houseNumber' => '11',
                    'houseNumberParts' => [
                        'base' => '11',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Tür 18',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Tür',
                    'houseNumber' => '18',
                    'houseNumberParts' => [
                        'base' => '18',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Çevreyolu Cd. No:19',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Çevreyolu Cd.',
                    'houseNumber' => '19',
                    'houseNumberParts' => [
                        'base' => '19',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Kerkstraat 3HS',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Kerkstraat',
                    'houseNumber' => '3HS',
                    'houseNumberParts' => [
                        'base' => '3',
                        'extension' => 'HS',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Kerkstraat 3-HS',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Kerkstraat',
                    'houseNumber' => '3-HS',
                    'houseNumberParts' => [
                        'base' => '3',
                        'extension' => 'HS',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Hollandweg1A',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Hollandweg',
                    'houseNumber' => '1A',
                    'houseNumberParts' => [
                        'base' => '1',
                        'extension' => 'A',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Niederer Weg 20 B',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Niederer Weg',
                    'houseNumber' => '20 B',
                    'houseNumberParts' => [
                        'base' => '20',
                        'extension' => 'B',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Kerkstraat 3 HS App. 13',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Kerkstraat',
                    'houseNumber' => '3 HS',
                    'houseNumberParts' => [
                        'base' => '3',
                        'extension' => 'HS',
                    ],
                    'additionToAddress2' => 'App. 13',
                ],
            ],
            [
                'Postbus 3099',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Postbus',
                    'houseNumber' => '3099',
                    'houseNumberParts' => [
                        'base' => '3099',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Nieder-Ramstädter Str. 181A WG B15',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Nieder-Ramstädter Str.',
                    'houseNumber' => '181A',
                    'houseNumberParts' => [
                        'base' => '181',
                        'extension' => 'A',
                    ],
                    'additionToAddress2' => 'WG B15',
                ],
            ],
            [
                'Poststr. 15-WG2',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Poststr.',
                    'houseNumber' => '15-WG2',
                    'houseNumberParts' => [
                        'base' => '15',
                        'extension' => 'WG2',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Reitelbauerstr. 7 1/2',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Reitelbauerstr.',
                    'houseNumber' => '7 1/2',
                    'houseNumberParts' => [
                        'base' => '7',
                        'extension' => '1/2',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Schegargasse 13-15/8/6',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Schegargasse',
                    'houseNumber' => '13-15/8/6',
                    'houseNumberParts' => [
                        'base' => '13',
                        'extension' => '15/8/6',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Breitenstr. 13/15/8/6',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Breitenstr.',
                    'houseNumber' => '13/15/8/6',
                    'houseNumberParts' => [
                        'base' => '13',
                        'extension' => '15/8/6',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Österreicher Weg 12A/8/6',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Österreicher Weg',
                    'houseNumber' => '12A/8/6',
                    'houseNumberParts' => [
                        'base' => '12',
                        'extension' => 'A/8/6',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Karlstr. 1 c/o Breitner',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Karlstr.',
                    'houseNumber' => '1',
                    'houseNumberParts' => [
                        'base' => '1',
                        'extension' => '',
                    ],
                    'additionToAddress2' => 'c/o Breitner',
                ],
            ],
            [
                'Alan-Turing-Straße 12A/8/6 c/o Victory',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Alan-Turing-Straße',
                    'houseNumber' => '12A/8/6',
                    'houseNumberParts' => [
                        'base' => '12',
                        'extension' => 'A/8/6',
                    ],
                    'additionToAddress2' => 'c/o Victory',
                ],
            ],
            [
                'Grace Hopper Av. 12c/o3',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Grace Hopper Av.',
                    'houseNumber' => '12c/o3',
                    'houseNumberParts' => [
                        'base' => '12',
                        'extension' => 'c/o3',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Katherine Johnson Street 12c/o3 c/o Dorothy Vaughan',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Katherine Johnson Street',
                    'houseNumber' => '12c/o3',
                    'houseNumberParts' => [
                        'base' => '12',
                        'extension' => 'c/o3',
                    ],
                    'additionToAddress2' => 'c/o Dorothy Vaughan',
                ],
            ],
            [
                'Mary Jackson Avenue 1921/Apr ℅ West Area Computing',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Mary Jackson Avenue',
                    'houseNumber' => '1921/Apr',
                    'houseNumberParts' => [
                        'base' => '1921',
                        'extension' => 'Apr',
                    ],
                    'additionToAddress2' => '℅ West Area Computing',
                ],
            ],
            [
                'Hauptstraße 27 Haus 1',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Hauptstraße',
                    'houseNumber' => '27',
                    'houseNumberParts' => [
                        'base' => '27',
                        'extension' => '',
                    ],
                    'additionToAddress2' => 'Haus 1',
                ],
            ],
            [
                'Rosentiefe 13 A Skulptur 5',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Rosentiefe',
                    'houseNumber' => '13 A',
                    'houseNumberParts' => [
                        'base' => '13',
                        'extension' => 'A',
                    ],
                    'additionToAddress2' => 'Skulptur 5',
                ],
            ],
            [
                'Torwolds Road 123 APT 3',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Torwolds Road',
                    'houseNumber' => '123',
                    'houseNumberParts' => [
                        'base' => '123',
                        'extension' => '',
                    ],
                    'additionToAddress2' => 'APT 3',
                ],
            ],
            [
                'Denis Ritchie Road 3 Building C',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Denis Ritchie Road',
                    'houseNumber' => '3',
                    'houseNumberParts' => [
                        'base' => '3',
                        'extension' => '',
                    ],
                    'additionToAddress2' => 'Building C',
                ],
            ],
            [
                'Brian-Kernighan-Straße 3 LVL3C',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Brian-Kernighan-Straße',
                    'houseNumber' => '3',
                    'houseNumberParts' => [
                        'base' => '3',
                        'extension' => '',
                    ],
                    'additionToAddress2' => 'LVL3C',
                ],
            ],
            [
                'Brian-Kernighan-Straße 3 LVL3C Zimmer 12',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Brian-Kernighan-Straße',
                    'houseNumber' => '3',
                    'houseNumberParts' => [
                        'base' => '3',
                        'extension' => '',
                    ],
                    'additionToAddress2' => 'LVL3C Zimmer 12',
                ],
            ],
            [
                'Brian-Kernighan-Straße 3 B 8. Stock App. C',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Brian-Kernighan-Straße',
                    'houseNumber' => '3 B',
                    'houseNumberParts' => [
                        'base' => '3',
                        'extension' => 'B',
                    ],
                    'additionToAddress2' => '8. Stock App. C',
                ],
            ],
            [
                'Brian-Kernighan-Straße 3 B 8th Floor App. C',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Brian-Kernighan-Straße',
                    'houseNumber' => '3 B',
                    'houseNumberParts' => [
                        'base' => '3',
                        'extension' => 'B',
                    ],
                    'additionToAddress2' => '8th Floor App. C',
                ],
            ],
            [
                'Beispielstraße, Nr 12',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Beispielstraße',
                    'houseNumber' => '12',
                    'houseNumberParts' => [
                        'base' => '12',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Beispielstraße Nr. 12',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Beispielstraße',
                    'houseNumber' => '12',
                    'houseNumberParts' => [
                        'base' => '12',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'No. 10 Drowning Street',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Drowning Street',
                    'houseNumber' => '10',
                    'houseNumberParts' => [
                        'base' => '10',
                        'extension' => '',
                    ],
                    'additionToAddress2' => '',
                ],
            ],
            [
                'Beispielstraße Nr 12 WG Nr 13',
                [
                    'additionToAddress1' => '',
                    'streetName' => 'Beispielstraße',
                    'houseNumber' => '12',
                    'houseNumberParts' => [
                        'base' => '12',
                        'extension' => '',
                    ],
                    'additionToAddress2' => 'WG Nr 13',
                ],
            ],
        ];
    }

    /**
     * @dataProvider invalidAddressesProvider
     * @expectedException \Pickware\AddressSplitter\AddressSplitterException
     *
     * @param string $address
     */
    public function testInvalidAddress($address)
    {
        AddressSplitter::splitAddress($address);
    }

    /**
     * @return array
     */
    public function invalidAddressesProvider()
    {
        return [
            ['House number missing'],
            ['123'],
            ['#101'],
        ];
    }
}
