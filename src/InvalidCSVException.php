<?php declare(strict_types=1);

/**
 * This file is part of CSVSpeaker, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019-2020 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *   GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *   https://www.phpexperts.pro/
 *   https://github.com/phpexpertsinc/CSVSpeaker
 *
 * This file is licensed under the MIT License.
 */

namespace PHPExperts\CSVSpeaker;

use RuntimeException;
use Throwable;

class InvalidCSVException extends RuntimeException
{
    public function __construct(string $fullFilename, int $lineNumber, array $headers, array $data, Throwable $previous = null)
    {
        $headersString = implode(', ', $headers);
        $dataString = implode(', ', $data);
        $message = <<<ERROR
Invalid CSV has been encountered @ $fullFilename:$lineNumber: (COLUMN MISMATCH) [$headersString] vs [$dataString]    
ERROR;
        parent::__construct($message, 1, $previous);
    }
}
