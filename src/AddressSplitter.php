<?php
namespace Pickware\AddressSplitter;

use RuntimeException;

class AddressSplitter
{
    /**
     * @var array
     */
    private static $generalNumberPrefixes = [
        // English
        'No [.:]?',
        'Nos ([.:]|\\s)',
        'Number \\s',

        // German
        'Nr [.:]?',
        'Nummer \\s',

        // Other
        '№ [.:]?',
        'Nº [.:]?',
        'n° [.:]?',
    ];

    /**
     * @var array
     */
    private static $additionalHouseNumberPrefixes = [
        // German
        'Hausnummer \\s',
        'Hausnr [.:]?',
    ];

    /**
     * @return string
     */
    private static function getHouseNumberPrefixes()
    {
        return self::getRegexForNumberPrefixes(array_merge(self::$generalNumberPrefixes, self::$additionalHouseNumberPrefixes));
    }

    /**
     * @return string
     */
    private static function getGeneralNumberPrefixes()
    {
        return self::getRegexForNumberPrefixes(self::$generalNumberPrefixes);
    }

    /**
     * Creates an anonymous group matching the supplied alternative regular expressions for number prefixes.
     *
     * The generated regular expression
     *
     * * assumes 'x' and 'u' pattern modifiers,
     *
     * * assumes each alternative must either be at the start of the string or be preceded by whitespace,
     *
     * * assumes each alternative to be case insensitive.
     *
     * @param array $numberPrefixes a list of regular expressions (assuming 'x' and 'u' flags)
     * @return string a regular expression for an anonymous group matching the alternatives supplied in the argument
     */
    private static function getRegexForNumberPrefixes(array $numberPrefixes)
    {
         return self::concatenateRegexAlternatives(array_map(function ($numberPrefix) {
             return '(?: ^ | (?<=\\s)) (?i: ' . $numberPrefix . ' )';
         }, $numberPrefixes));
    }

    /**
     * @param array $alternatives
     * @return string
     */
    private static function concatenateRegexAlternatives(array $alternatives)
    {
        return '(?:' . implode('|', $alternatives) . ')';
    }

    /**
     * This function splits an address line like for example "Pallaswiesenstr. 45 App 231" into its individual parts.
     * Supported parts are additionToAddress1, streetName, houseNumber and additionToAddress2. AdditionToAddress1
     * and additionToAddress2 contain additional information that is given at the start and the end of the string, respectively.
     *
     * @param string $address
     * @return array
     * @throws AddressSplitterException
     */
    public static function splitAddress($address)
    {
        $houseNumberPrefixes = self::getHouseNumberPrefixes();
        $numberPrefixes = self::getGeneralNumberPrefixes();

        /* Matching this group signifies the following text is part of
         * additionToAddress2.
         *
         * See [1] for some of the English language stop words and abbreviations.
         *
         * [1] <https://web.archive.org/web/20180410130330/http://maf.directory/zp4/abbrev.html>
         */
        $addition2Introducers = '(?:

            # {{{ Additions relating to who (a natural person) is addressed

            \\s+ [Cc] \\s* \\/ \\s* [Oo] \\s
            | ℅
            | \\s+ care \\s+ of \\s+

            # German, Swiss, Austrian
            | \\s+ (?: p|p.\\s*|per\\s+ ) (?: A|A.|Adr.|(?<=\\s)Adresse ) \\s
            | \\s+ p. \\s* A. \\s
            | \\s+ (?: z | z.\\s* | zu\\s+ ) (?: Hd|Hd.|(?<=\\s)Händen|(?<=\\s)Haenden|(?<=\\s)Handen) \\s+

            ## o. V. i. A. = oder Vertreter im Amt
            | \\s+ (?: o | o.\\s* | oder\\s+ )
                (?: V | V.\\s* | (?<=\\s)Vertreter\\s+ )
                (?: i | i.\\s* | (?<=\\s)im\\s+ )
                (?: A | A.\\s* | (?<=\\s)Amt\\s+ )

            # }}}
            # {{{ Additions which further specify more precisely the location

            | \\s+ (?: Haus ' . $numberPrefixes . '? ) \\s
            | \\s+ (?: WG | W\\.G\\. | WG\\. | Wohngemeinschaft ) ' . $numberPrefixes .'? ($ | \\s)
            | \\s+ (?: [Aa]partment | APT \\.? | Apt \\.? ) ' . $numberPrefixes .'? \\s
            | \\s+ (?: [Ff]lat ) ' . $numberPrefixes .'? \\s
            | (?: # Numeric-based location specifiers (e.g., "3. Stock"):
                \\s+
                (?:
                    [\\p{N}]+ # A number, …
                    (?i: st | nd | rd | th)? # …, optionally followed by an English number suffix
                    \\.? # …, followed by an optional dot,
                    \\s* # …, followed by optional spacing
                )?
                (?: # Specifying category:
                    (?i: Stock | Stockwerk)
                    | App \\.? | Apt \\.? | (?i: Appartment | Apartment)
                )
                # At the end of the string or followed by a space
                (?: $ | \\s)
            )
            | (?:
                \\s+ (?:
                    # English language stop words wrt location from source [1]
                    # (extracted only those which may not be _exclusively_ part of
                    # street names):
                    | ANX \\.? | (?i: ANNEX)
                    | APT \\.? | (?i: APARTMENT)
                    | ARC \\.? | (?i: ARCADE)
                    | AVE \\.? | (?i: AVENUE)
                    | BSMT \\.? | (?i: BASEMENT)
                    | BLDG \\.? | (?i: BUILDING)
                    | CP \\.? | (?i: CAMP)
                    | COR \\.? | (?i: CORNER)
                    | CORS \\.? | (?i: CORNERS)
                    | CT \\.? | (?i: COURT)
                    | CTS \\.? | (?i: COURTS)
                    | DEPT \\.? | (?i: DEPARTMENT)
                    | DV \\.? | (?i: DIVIDE)
                    | EST \\.? | (?i: ESTATE)
                    | EXT \\.? | (?i: EXTENSION)
                    | FRY \\.? | (?i: FERRY)
                    | FLD \\.? | (?i: FIELD)
                    | FLDS \\.? | (?i: FIELDS)
                    | FLT \\.? | (?i: FLAT)
                    | FL \\.? | (?i: FLOOR)
                    | FRNT \\.? | (?i: FRONT)
                    | GDNS \\.? | (?i: GARDEN)
                    | GDNS \\.? | (?i: GARDENS)
                    | GTWY \\.? | (?i: GATEWAY)
                    | GRN \\.? | (?i: GREEN)
                    | GRV \\.? | (?i: GROVE)
                    | HNGR \\.? | (?i: HANGER)
                    | HBR \\.? | (?i: HARBOR)
                    | HVN \\.? | (?i: HAVEN)
                    | KY \\.? | (?i: KEY)
                    | LBBY \\.? | (?i: LOBBY)
                    | LCKS \\.? | (?i: LOCK)
                    | LCKS \\.? | (?i: LOCKS)
                    | LDG \\.? | (?i: LODGE)
                    | MNR \\.? | (?i: MANOR)
                    | OFC \\.? | (?i: OFFICE)
                    | PKWY \\.? | (?i: PARKWAY)
                    | PH \\.? | (?i: PENTHOUSE)
                    | PRT \\.? | (?i: PORT)
                    | RADL \\.? | (?i: RADIAL)
                    | RM \\.? | (?i: ROOM)
                    | SPC \\.? | (?i: SPACE)
                    | SQ \\.? | (?i: SQUARE)
                    | STA \\.? | (?i: STATION)
                    | STE \\.? | (?i: SUITE)
                    | TER \\.? | (?i: TERRACE)
                    | TRAK \\.? | (?i: TRACK)
                    | TRL \\.? | (?i: TRAIL)
                    | TRLR \\.? | (?i: TRAILER)
                    | TUNL \\.? | (?i: TUNNEL)
                    | VW \\.? | (?i: VIEW)
                    | VIS \\.? | (?i: VISTA)

                    # Custom custom additions:
                    | (?i: Story | Storey)
                    | LVL \\.? | (?i: Level)
                )
                ' . $numberPrefixes . '?
                # May optionally be followed directly by a number+letter
                # combination (e.g., "LVL3C"):
                (?: [\\p{N}]+[\\p{L}]* )?
                # Occurs at the end of the string or followed by a space:
                ($ | \\s)
            )

            # Heuristic to match location specifiers. These must not be
            # conflated with house number extensions as in "12 AB". Hence
            # our heuristic is at least 3 letters with the first letter being
            # spelled as a capital. E.g., it would match "Haus", "Gebäude" or
            # "Arbeitspl.", but not "AAB".
            | \\s+ ( [\\p{Lu}\\p{Lt}] [\\p{Ll}\\p{Lo}]{2,}  \\.? ) ($ | \\s)

            # }}}
        )';

        $regex = '
            /\\A\\s*
            (?: #########################################################################
                # Option A: [<Addition to address 1>] <House number> <Street name>      #
                # [<Addition to address 2>]                                             #
                #########################################################################
                (?:(?P<A_Addition_to_address_1>.*?),\\s*)? # Addition to address 1
                (?: ' . $houseNumberPrefixes . ' \\s*)?
                (?P<A_House_number_match>
                     (?P<A_House_number_base>
                        \\pN+(\\s+\\d+\\/\\d+)?
                     )
                     (?:
                        \\s*[\\-\\/\\.]?\\s*
                        (?P<A_House_number_extension>(?:[a-zA-Z\\pN]){1,2})
                        \\s+
                     )?
                )
            \\s*,?\\s*
                (?P<A_Street_name>(?:[a-zA-Z]\\s*|\\pN\\pL{2,}\\s\\pL)\\S[^,#]*?(?<!\\s)) # Street name
            \\s*(?:(?:[,\\/]|(?=\\#))\\s*(?!\\s* ' . $houseNumberPrefixes . ')
                (?P<A_Addition_to_address_2>(?!\\s).*?))? # Addition to address 2
            |   #########################################################################
                # Option B: [<Addition to address 1>] <Street name> <House number>      #
                # [<Addition to address 2>]                                             #
                #########################################################################
                (?:(?P<B_Addition_to_address_1>.*?),\\s*(?=.*[,\\/]))? # Addition to address 1
                (?!\\s* ' . $houseNumberPrefixes . ')
                (?P<B_Street_name>[^0-9# ]\\s*\\S(?:[^,#](?!\\b\\pN+\\s))*?(?<!\\s)) # Street name
            \\s*[\\/,]?\\s*(?:\\s ' . $houseNumberPrefixes . ')?\\s*
                (?P<B_House_number_match>
                     (?P<B_House_number_base>
                        \\pN+
                     )
                     (?:
                        # Match house numbers that are (optionally) amended
                        # by a dash (e.g., 12-13) or slash (e.g., 12\\/A):
                        (?: \\s*[\\-\\/]\\s* )*
                        (?P<B_House_number_extension>
                            (?:
                                # Do not match "care-of"-like additions as
                                # house numbers:
                                (?!' . $addition2Introducers .')
                                \\s*[\\pL\\pN]+
                            )
                            # Match any further slash- or dash-based house
                            # number extensions:
                            (?:
                                # Do not match "care-of"-like additions as
                                # house numbers:
                                (?!' . $addition2Introducers .')
                                # Match any (optionally space-separated)
                                # additional parts of house numbers after
                                # slashes or dashes.
                                \\s* [\\-\\/] \\s*
                                [\\pL\\pN]+
                            )*
                        )
                     )?
                ) # House number
                (?:
                    (?:\\s*[-,\\/]|(?=\\#)|\\s)
                    \\s*
                    (?!\\s* ' . $houseNumberPrefixes . ' )
                    \\s*
                    (?P<B_Addition_to_address_2>(?!\\s).*?)
                )?
            )
            \\s*\\Z/xu';

        $result = preg_match($regex, $address, $matches);
        if ($result === 0) {
            throw new AddressSplitterException(AddressSplitterException::CODE_ADDRESS_SPLITTING_ERROR, $address);
        }
        if ($result === false) {
            throw new RuntimeException(sprintf('Error occurred while trying to split address \'%s\'', $address));
        }

        if (!empty($matches['A_Street_name'])) {
            return [
                'additionToAddress1' => $matches['A_Addition_to_address_1'],
                'streetName' => $matches['A_Street_name'],
                'houseNumber' => $matches['A_House_number_match'],
                'houseNumberParts' => [
                    'base' => $matches['A_House_number_base'],
                    'extension' => isset($matches['A_House_number_extension']) ? trim($matches['A_House_number_extension']) : '',
                ],
                'additionToAddress2' => (isset($matches['A_Addition_to_address_2'])) ? $matches['A_Addition_to_address_2'] : '',
            ];
        }

        return [
            'additionToAddress1' => $matches['B_Addition_to_address_1'],
            'streetName' => $matches['B_Street_name'],
            'houseNumber' => $matches['B_House_number_match'],
            'houseNumberParts' => [
                'base' => $matches['B_House_number_base'],
                'extension' => isset($matches['B_House_number_extension']) ? trim($matches['B_House_number_extension']) : '',
            ],
            'additionToAddress2' => isset($matches['B_Addition_to_address_2']) ? $matches['B_Addition_to_address_2'] : '',
        ];
    }

    /**
     * @param string $houseNumber A house number string to split in base and extension
     * @return array
     * @throws AddressSplitterException
     */
    public static function splitHouseNumber($houseNumber)
    {
        $regex = '
            /
            \\A\\s* # Trim white spaces at the beginning
            (?: ' . self::getHouseNumberPrefixes() . '?\\s*)?
            (?:\\#\\s*)? # Trim #
            (?<House_number_base>
                [\\pN]+ # House Number base (only the number)
            )
            \\s*[\\/\\-\\.]?\\s* # Separator (optional)
            (?<House_number_extension> # House number extension (optional)
                .*? # Here we allow every character. Everything after the separator is interpreted as extension
            ) 
            \\s*\\Z # Trim white spaces at the end
            /xu'; // Option (u)nicode and e(x)tended syntax

        $result = preg_match($regex, $houseNumber, $matches);

        if ($result === 0) {
            throw new AddressSplitterException(AddressSplitterException::CODE_HOUSE_NUMBER_SPLITTING_ERROR, $houseNumber);
        }
        if ($result === false) {
            throw new RuntimeException(sprintf('Error occurred while trying to house number \'%s\'', $houseNumber));
        }

        return [
            'base' => $matches['House_number_base'],
            'extension' => $matches['House_number_extension'],
        ];
    }
}
