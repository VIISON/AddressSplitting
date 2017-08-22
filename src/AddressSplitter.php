<?php

namespace VIISON\AddressSplitter;

class AddressSplitter
{
    /**
     * This function splits an address line like for example "Pallaswiesenstr. 45 App 231" into its individual parts.
     * Supported parts are additionToAddress1, streetName, houseNumber and additionToAddress2. AdditionToAddress1
     * and additionToAddress2 contain additional information that is given at the start and the end of the string, respectively.
     * Unit tests for testing the regular expression that this function uses exist over at https://regex101.com/r/vO5fY7/1.
     * More information on this functionality can be found at http://blog.viison.com/post/115849166487/shopware-5-from-a-technical-point-of-view#address-splitting.
     *
     * @param string $address
     * @return array
     */
    public static function splitAddress($address)
    {
        $regex = '
            /\A\s*
            (?: #########################################################################
                # Option A: [<Addition to address 1>] <House number> <Street name>      #
                # [<Addition to address 2>]                                             #
                #########################################################################
                (?:(?P<A_Addition_to_address_1>.*?),\s*)? # Addition to address 1
            (?:No\.\s*)?
                (?P<A_House_number_match>
                     (?P<A_House_number_base>
                        \pN+
                     )
                     (?:
                        \s*[\-\/\.]?\s*
                        (?P<A_House_number_extension>(?:[a-zA-Z\pN]){1,2})
                        \s+
                     )?
                )
            \s*,?\s*
                (?P<A_Street_name>(?:[a-zA-Z]\s*|\pN\pL{2,}\s\pL)\S[^,#]*?(?<!\s)) # Street name
            \s*(?:(?:[,\/]|(?=\#))\s*(?!\s*No\.)
                (?P<A_Addition_to_address_2>(?!\s).*?))? # Addition to address 2
            |   #########################################################################
                # Option B: [<Addition to address 1>] <Street name> <House number>      #
                # [<Addition to address 2>]                                             #
                #########################################################################
                (?:(?P<B_Addition_to_address_1>.*?),\s*(?=.*[,\/]))? # Addition to address 1
                (?!\s*No\.)(?P<B_Street_name>[^0-9# ]\s*\S(?:[^,#](?!\b\pN+\s))*?(?<!\s)) # Street name
            \s*[\/,]?\s*(?:\sNo[.:])?\s*
                (?P<B_House_number_match>
                     (?P<B_House_number_base>
                        \pN+
                     )
                     (?:
                        (?:
                            \s*[\-\/\.]?\s*
                            (?P<B_House_number_extension>(?:[a-zA-Z\pN]){1,2})
                            \s*
                        )?
                        |
                        (?<!\s)
                     )
                ) # House number
                (?:
                    (?:\s*[-,\/]|(?=\#)|\s)\s*(?!\s*No\.)\s*
                    (?P<B_Addition_to_address_2>(?!\s).*?)
                )?
            )
            \s*\Z/xu';

        $result = preg_match($regex, $address, $matches);
        if ($result === 0) {
            throw new \InvalidArgumentException(sprintf('Address \'%s\' could not be splitted into street name and house number', $address));
        } elseif ($result === false) {
            throw new \RuntimeException(sprintf('Error occurred while trying to split address \'%s\'', $address));
        }

        if (!empty($matches['A_Street_name'])) {
            return array(
                'additionToAddress1' => $matches['A_Addition_to_address_1'],
                'streetName' => $matches['A_Street_name'],
                'houseNumber' => $matches['A_House_number_match'],
                'houseNumberParts' => array(
                    'base' => $matches['A_House_number_base'],
                    'extension' => isset($matches['A_House_number_extension']) ? $matches['A_House_number_extension'] : ''
                ),
                'additionToAddress2' => (isset($matches['A_Addition_to_address_2'])) ? $matches['A_Addition_to_address_2'] : ''
            );
        } else {
            return array(
                'additionToAddress1' => $matches['B_Addition_to_address_1'],
                'streetName' => $matches['B_Street_name'],
                'houseNumber' => $matches['B_House_number_match'],
                'houseNumberParts' => array(
                    'base' => $matches['B_House_number_base'],
                    'extension' => isset($matches['B_House_number_extension']) ? $matches['B_House_number_extension'] : ''
                ),
                'additionToAddress2' => isset($matches['B_Addition_to_address_2']) ? $matches['B_Addition_to_address_2'] : ''
            );
        }
    }
}
