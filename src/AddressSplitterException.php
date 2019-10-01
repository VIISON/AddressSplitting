<?php
namespace Pickware\AddressSplitter;

use Exception;

class AddressSplitterException extends Exception
{
    const CODE_ADDRESS_SPLITTING_ERROR = 0x01;
    const CODE_HOUSE_NUMBER_SPLITTING_ERROR = 0x02;
    const CODE_UNKNOWN_ERROR = 0xFF;

    /**
     * @var array Predefined error message
     */
    private static $errorMessages = [
        self::CODE_ADDRESS_SPLITTING_ERROR => 'Address \'%s\' could not be splitted into street name and house number.',
        self::CODE_HOUSE_NUMBER_SPLITTING_ERROR => 'House number \'%s\' could not be splitted into base and extension.',
        self::CODE_UNKNOWN_ERROR => 'Unknown error',
    ];

    /**
     * Create new SplittingException, automatically adds a meaningful error message when the error code is known.
     *
     * @param int $code
     * @param string $splittingSubject The string that was tried to split
     */
    public function __construct($code, $splittingSubject)
    {
        if (!array_key_exists($code, self::$errorMessages)) {
            $code = self::CODE_UNKNOWN_ERROR;
        }
        $message = sprintf(self::$errorMessages[$code], $splittingSubject);

        parent::__construct($message, $code);
    }
}
