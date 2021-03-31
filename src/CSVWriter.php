<?php declare(strict_types=1);

/**
 * This file is part of CSVSpeaker, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019-2021 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *   GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *   https://www.phpexperts.pro/
 *   https://github.com/phpexpertsinc/CSVSpeaker
 *
 * This file is licensed under the MIT License.
 */

namespace PHPExperts\CSVSpeaker;

use RuntimeException;

final class CSVWriter
{
    /** @var string The contents of the CSV file. */
    private $csv = '';

    /** @var string */
    private $header;

    private function convertToCsv(array $row, $delimiter = ','): string
    {
        if (!($fh = fopen('php://memory', 'w+'))) {
            // @codeCoverageIgnoreStart - Untestable extreme edge case.
            throw new RuntimeException('Ran out of memory.');
            // @codeCoverageIgnoreEnd
        }

        fputcsv($fh, $row, $delimiter);
        rewind($fh);
        if (!($csv = stream_get_contents($fh))) {
            // @codeCoverageIgnoreStart - Untestable extreme edge case.
            throw new RuntimeException('Could not read the generated CSV file.');
            // @codeCoverageIgnoreEnd
        }
        fclose($fh);

        return $csv;
    }

    /**
     * Taken from https://stackoverflow.com/a/173479/430062.
     *
     * @param array $arr
     *
     * @return bool
     */
    private function isAssocArray(array $arr)
    {
        if (array() === $arr) {
            return false;
        }

        if (array_key_exists(0, $arr)) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * WARNING: This method overwrite the CSV.
     *
     * @param array $columnNames
     */
    private function addHeader(array $columnNames): void
    {
        $newHeader = $this->convertToCsv($columnNames);

        if ($this->header === $newHeader) {
            return;
        }

        $this->csv .= $newHeader;
        $this->header = $newHeader;
    }

    public function addRow(array $row): void
    {
        if ($this->isAssocArray($row)) {
            $this->addHeader(array_keys($row));
        }

        $this->csv .= $this->convertToCsv($row);
    }

    public function addRows(array $rows): void
    {
        foreach ($rows as $row) {
            $this->addRow($row);
        }
    }

    public function getCSV(): string
    {
        return $this->csv;
    }
}
