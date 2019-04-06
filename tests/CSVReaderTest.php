<?php declare(strict_types=1);

namespace PHPExperts\CSVSpeaker\Tests;

use PHPExperts\CSVSpeaker\CSVReader;
use PHPUnit\Framework\TestCase;
use SplFileObject;

class CSVReaderTest extends TestCase
{
    public function testWillConvertACsvStringToAnArray()
    {
        $csv = <<<CSV
a,b,c,d
CSV;

        $expected = [
            ['a', 'b', 'c', 'd'],
        ];
        $actual = CSVReader::fromString($csv, false)->toArray();

        self::assertEquals($expected, $actual);
    }

    public function testWillOutputACsvFileToAnArray()
    {
        $csv = <<<CSV
a,b,c,d
e,f,g,h
CSV;
        $csvFile = new SplFileObject('php://memory', 'r+');
        $csvFile->fwrite($csv);
        $csvFile->rewind();

        $expected = [
            ['a', 'b', 'c', 'd'],
            ['e', 'f', 'g', 'h'],
        ];

        $actual = CSVReader::fromFile($csvFile, false)->toArray();
        self::assertEquals($expected, $actual);
    }

    public function testWillUseTheFirstRowAsArrayKeysByDefault()
    {
        $csv = <<<CSV
"First","Last",Age
John,Galt,37
Mary,Jane,27
CSV;

        $expected = [
            ['First' => 'John', 'Last' => 'Galt', 'Age' => 37],
            ['First' => 'Mary', 'Last' => 'Jane', 'Age' => 27],
        ];
        $actual = CSVReader::fromString($csv)->toArray();
        self::assertEquals($expected, $actual);
    }
}
