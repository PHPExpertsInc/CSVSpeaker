<?php declare(strict_types=1);

/**
 * This file is part of CSVSpeaker, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *   GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *   https://www.phpexperts.pro/
 *   https://github.com/phpexpertsinc/CSVSpeaker
 *
 * This file is licensed under the MIT License.
 */

namespace PHPExperts\CSVSpeaker;

use InvalidArgumentException;
use SplFileObject;

class CSVReader
{
    /** @var bool Whether or not the first row is the header. */
    private $firstIsHeader;

    /** @var SplFileObject */
    private $csvFile;

    private function __construct(SplFileObject $csvFile, bool $firstIsHeader = true)
    {
        $this->csvFile = $csvFile;
        $this->firstIsHeader = $firstIsHeader;
    }

    public function __destruct()
    {
        unset($this->csvFile);
    }

    public static function fromString(string $csv, bool $firstIsHeader = true, string $delimiter = ',')
    {
        $csvFile = new SplFileObject('php://memory', 'r+');
        $csvFile->setCsvControl($delimiter);
        $csvFile->fwrite($csv);
        $csvFile->rewind();

        return new self($csvFile, $firstIsHeader);
    }

    public static function fromFile($file, bool $firstIsHeader = true, string $delimiter = ','): self
    {
        $getSplFile = function ($file): SplFileObject {
            if ($file instanceof SplFileObject) {
                return $file;
            }
            if (is_string($file)) {
                return new SplFileObject($file);
            } else {
                throw new InvalidArgumentException('Invalid file type.');
            }
        };

        $csvFile = $getSplFile($file);
        $csvFile->setCsvControl($delimiter);

        return new self($csvFile, $firstIsHeader);
    }

    private function readCSVGenerator(array $headers)
    {
        $headerCount = count($headers);
        $lineNumber = 0;
        while (!$this->csvFile->eof()) {
            $data = $this->csvFile->fgetcsv();
            ++$lineNumber;

            // Return [] if it is not CSV.
            if ($data === [null] || !is_array($data) || count($data) === 1) {
                continue;
            }

            if (empty($headers)) {
                yield $data;

                continue;
            }

            // Sanity check to avoid `Error: array_combine(): Both parameters should have an equal number of elements`
            if (count($data) != $headerCount) {
                throw new InvalidCSVException($this->csvFile->getPathname(), $lineNumber, $headers, $data);
            }

            yield array_combine($headers, $data);
        }
    }

    public function toArray()
    {
        $headers = [];
        if ($this->firstIsHeader) {
            $headers = (array) $this->csvFile->fgetcsv();
        }

        $output = [];
        foreach ($this->readCSVGenerator($headers) as $row) {
            $output[] = $row;
        }

        return $output;
    }
}
