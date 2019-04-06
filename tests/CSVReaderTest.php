<?php declare(strict_types=1);

namespace PHPExperts\CSVSpeaker\Tests;

use InvalidArgumentException;
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

    public function testCanBeLoadedViaAFileName()
    {
        $tempCSVfilename = tempnam(sys_get_temp_dir(), 'tmpcsv-') . '.csv';
        $csv = <<<CSV
a,b,c,d
e,f,g,h

CSV;
        file_put_contents($tempCSVfilename, $csv);

        $expected = [
            ['a', 'b', 'c', 'd'],
            ['e', 'f', 'g', 'h'],
        ];

        $actual = CSVReader::fromFile($tempCSVfilename, false)->toArray();
        self::assertEquals($expected, $actual);
    }

    public function testThrowAnExceptionWhenGivenAnInvalidFileType()
    {
        self::expectException(InvalidArgumentException::class);

        CSVReader::fromFile(1234);
    }

    public function testWillReturnAnEmptyArrayIfInputIsNotProperCsv()
    {
        $expected = [];
        $actual = CSVReader::fromString('asdfasdfasdf---3')->toArray();
        self::assertEquals($expected, $actual);

        $actual = CSVReader::fromString('asdfasdfasdf---3', false)->toArray();
        self::assertEquals($expected, $actual);

        $actual = CSVReader::fromString('', false)->toArray();
        self::assertEquals($expected, $actual);

        $actual = CSVReader::fromFile(new SplFileObject(tempnam(sys_get_temp_dir(), 'asdf')))->toArray();
        self::assertEquals($expected, $actual);
    }
}
