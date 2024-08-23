# CSVSpeaker

[![TravisCI](https://travis-ci.org/phpexpertsinc/CSVSpeaker.svg?branch=master)](https://travis-ci.org/phpexpertsinc/CSVSpeaker)
[![Maintainability](https://api.codeclimate.com/v1/badges/78f849d7bdcca99eb720/maintainability)](https://codeclimate.com/github/phpexpertsinc/CSVSpeaker/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/78f849d7bdcca99eb720/test_coverage)](https://codeclimate.com/github/phpexpertsinc/CSVSpeaker/test_coverage)

CSVSpeaker is a PHP Experts, Inc., Project for easily converting CSV to/from arrays.

This library's main goal is to make it drop-dead simple to get an array from a CSV string,
and also vice versa.

**Special Note:** This project has been updated to support the deprecation of 
`fgetcsv` and `fputcsv`'s `$enclosure` parameter.

See [**CSV and PHP 8.4+**](http://archive.today/2024.08.23-131404/https://nyamsprod.com/blog/csv-and-php8-4/)
for details.

## Installation

Via Composer

```bash
composer require phpexperts/csv-speaker
```

## Usage

### Reading

#### Instantiating / Loading
```php
    // The CSVReader can be instantiated from a file via just the file's filename:
    $csvReader = CSVReader::fromFile('/tmp/my.csv');

    // or via a SplFileObject:
    $csvReader = CSVReader::fromFile(new SplFileObject('/tmp/my.csv'));

    // More simply, you can instantiate from any CSV string.
    $csvReader = CSVReader::fromString('a,b,c,d');
```

#### Convert To An array
```php
    $output = $csvReader->toArray();
    /* [
        ['a', 'b', 'c', 'd']
    ] */
```

#### The First Row becomes the Array Keys, by Default
```php
    $csv = <<<CSV
    "First Name","Last Name",Age
    "John","Galt",37
    CSV;

    $output = CSVReader::fromString($csv)->toArray();
    /* [
        ['First Name' => 'John', 'Last Name' => 'Galt', 'Age' => 37]
    ] */
```

#### You can also turn off headers:
```php
    $csv = <<<CSV
    "First Name","Last Name",Age
    "John","Galt",37
    CSV;

    $output = CSVReader::fromString($csv, false)->toArray();
    /* [
        ['First Name', 'Last Name', 'Age'],
        ['John',       'Galt',      '37']
    ] */
```

### Writing

#### Numerical Arrays
```php
    $input = [
        ['a', 'b', 'c'],
        ['d', 'e', 'f']
    ];
    $csvWriter = new CSVWriter();
    $csvWriter->addRow($input[0]);
    $csvWriter->addRow($input[1]);
    $csv = $csvWriter->getCSV();

    /* csv:
        a,b,c
        d,e,f
    */
```

#### Keyed Arrays (Hashmaps)
```php
    $input = [
        ['Name' => 'John Galt', 'Age' => 37],
        ['Name' => 'Mary Jane', 'Age' => 27],
    ];
    $csvWriter = new CSVWriter();
    $csvWriter->addRow($input[0]);
    $csvWriter->addRow($input[1]);
    $csv = $csvWriter->getCSV();

    /* csv:
        Name,Age
        "John Galt",37
        "Mary Jane",27
    */
```

# Use cases

PHPExperts\CSVSpeaker\CSVReader  
 ✔ Will convert a csv string to an array  
 ✔ Will output a csv file to an array  
 ✔ Will use the first row as array keys by default  
 ✔ Can be loaded via a file name  
 ✔ Throw an exception when given an invalid file type  
 ✔ Will return an empty array if input is not proper csv  

PHPExperts\CSVSpeaker\CSVWriter  
 ✔ Converts a simple array to csv  
 ✔ Can append rows to existing csv  
 ✔ Will set keys as header row  
 ✔ Can add multiple rows with the same header  
 ✔ Will gracefully ignore empty arrays


## ChangeLog

Please see the [changelog](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
phpunit
```

# Contributors

[Theodore R. Smith](https://www.phpexperts.pro/]) <theodore@phpexperts.pro>  
GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690  
CEO: PHP Experts, Inc.

## License

MIT license. Please see the [license file](LICENSE) for more information.

