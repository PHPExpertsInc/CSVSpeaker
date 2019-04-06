<?php declare(strict_types=1);

namespace PHPExperts\CSVSpeaker;

use SplFileObject;

final class CSVWriter
{
    /** @var string The contents of the CSV file. */
    private $csv = '';

    /** @var string */
    private $header;

    private function convertToCsv(array $row, $delimiter = ','): string
    {
        $fh = fopen('php://memory', 'w+');
        fputcsv($fh, $row, $delimiter);
        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        return $csv;
    }

    /**
     * Taken from https://stackoverflow.com/a/173479/430062
     *
     * @param array $arr
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

    public function getCSV(): string
    {
        return $this->csv;
    }
}
