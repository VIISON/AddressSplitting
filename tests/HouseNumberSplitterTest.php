<?php
namespace Pickware\AddressSplitter\Test;

use PHPUnit_Framework_TestCase;
use Pickware\AddressSplitter\AddressSplitter;

class HouseNumberSplitterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validHouseNumberProvider
     *
     * @param string $houseNumber
     * @param array $expected
     */
    public function testValidHouseNumbers($houseNumber, $expected)
    {
        self::assertSame($expected, AddressSplitter::splitHouseNumber($houseNumber));
    }

    /**
     * @return array
     */
    public function validHouseNumberProvider()
    {
        return [
            [
                '123',
                [
                    'base' => '123',
                    'extension' => '',
                ],
            ],
            [
                '12A',
                [
                    'base' => '12',
                    'extension' => 'A',
                ],
            ],
            [
                '13 B',
                [
                    'base' => '13',
                    'extension' => 'B',
                ],
            ],
            [
                '37 HS',
                [
                    'base' => '37',
                    'extension' => 'HS',
                ],
            ],
            [
                '  34/C',
                [
                    'base' => '34',
                    'extension' => 'C',
                ],
            ],
            [
                '#23 C',
                [
                    'base' => '23',
                    'extension' => 'C',
                ],
            ],
            [
                'No. 12 A',
                [
                    'base' => '12',
                    'extension' => 'A',
                ],
            ],
            [
                'No:12/B3 ',
                [
                    'base' => '12',
                    'extension' => 'B3',
                ],
            ],
            [
                '13/15/4/5',
                [
                    'base' => '13',
                    'extension' => '15/4/5',
                ],
            ],
        ];
    }
}
