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

namespace PHPExperts\CSVSpeaker\Tests;

use PHPExperts\CSVSpeaker\CSVWriter;
use PHPUnit\Framework\TestCase;

class CSVWriterTest extends TestCase
{
    /** @var CSVWriter */
    private $csvWriter;

    public function setUp(): void
    {
        $this->csvWriter = new CSVWriter();

        parent::setUp();
    }

    public function testConvertsASimpleArrayToCsv()
    {
        $input = ['John', 'Galt', 37];
        $expected = 'John,Galt,37' . "\n";
        $this->csvWriter->addRow($input);

        self::assertEquals($expected, $this->csvWriter->getCSV());
    }

    public function testCanAppendRowsToExistingCsv()
    {
        $input = [
            ['John', 'Galt', 37],
            ['Mary', 'Jane', 27],
        ];

        $expected = [
            'John,Galt,37' . "\n",
            'John,Galt,37' . "\n" .
            'Mary,Jane,27' . "\n",
        ];

        $this->csvWriter->addRow($input[0]);
        self::assertEquals($expected[0], $this->csvWriter->getCSV());

        $this->csvWriter->addRow($input[1]);
        self::assertEquals($expected[1], $this->csvWriter->getCSV());
    }

    public function testWillSetKeysAsHeaderRow()
    {
        $input = [
            ['First Name' => 'John', 'Last Name' => 'Galt', 'Age' => 37],
        ];

        $expected = <<<CSV
"First Name","Last Name",Age
John,Galt,37

CSV;

        $this->csvWriter->addRow($input[0]);
        $csv = $this->csvWriter->getCSV();

        self::assertEquals($expected, $csv);
    }

    public function testCanAddMultipleRowsWithTheSameHeader()
    {
        $input = [
            ['First Name' => 'John', 'Last Name' => 'Galt', 'Age' => 37],
            ['First Name' => 'Mary', 'Last Name' => 'Jane', 'Age' => 27],
        ];

        $expected = <<<CSV
"First Name","Last Name",Age
John,Galt,37
Mary,Jane,27

CSV;

        $this->csvWriter->addRow($input[0]);
        $this->csvWriter->addRow($input[1]);
        $csv = $this->csvWriter->getCSV();

        self::assertEquals($expected, $csv);
    }

    public function testCanAddMultipleRowsAtOnce()
    {
        $input = [
            ['First Name' => 'John', 'Last Name' => 'Galt', 'Age' => 37],
            ['First Name' => 'Mary', 'Last Name' => 'Jane', 'Age' => 27],
        ];

        $expected = <<<CSV
"First Name","Last Name",Age
John,Galt,37
Mary,Jane,27

CSV;

        $this->csvWriter->addRows($input);
        $csv = $this->csvWriter->getCSV();

        self::assertEquals($expected, $csv);
    }

    public function testWillGracefullyIgnoreEmptyArrays()
    {
        $this->csvWriter->addRow([]);
        $csv = $this->csvWriter->getCSV();
        self::assertEquals("\n", $csv);
    }
}
