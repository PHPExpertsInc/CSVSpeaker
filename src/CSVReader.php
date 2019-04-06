<?php declare(strict_types=1);

namespace PHPExperts\CSVSpeaker;

use InvalidArgumentException;
use RuntimeException;
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

    public static function fromString(string $csv, bool $firstIsHeader = true, string $delimiter=',')
    {
        $csvFile = new SplFileObject('php://memory', 'r+');
        $csvFile->setCsvControl($delimiter);
        $csvFile->fwrite($csv);
        $csvFile->rewind();

        return new self($csvFile, $firstIsHeader);
    }

    public static function fromFile($file, bool $firstIsHeader = true, string $delimiter=','): self
    {
        $getSplFile = function($file): SplFileObject {
            if ($file instanceof SplFileObject) {
                return $file;
            }
            else if (is_string($file)) {
                return new SplFileObject($file);
            }
            else {
                throw new InvalidArgumentException('Invalid file type.');
            }
        };

        $csvFile = $getSplFile($file);
        $csvFile->setCsvControl($delimiter);

        return new self($csvFile, $firstIsHeader);
    }

    private function readCSVGenerator(array $headers)
    {
        while (!$this->csvFile->eof()) {
            $data = $this->csvFile->fgetcsv();

            // Return [] if it is not CSV.
            if ($data === [null] || !is_array($data) || count($data) === 1) {
                continue;
            }

            if (empty($headers)) {
                yield $data;

                continue;
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
