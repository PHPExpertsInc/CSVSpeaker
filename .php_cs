<?php

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony'       => true,
        'elseif'         => false,
        'no_superfluous_elseif' => true,
        'yoda_style'     => false,
        'list_syntax'    => ['syntax'  => 'short'],
        'concat_space'   => ['spacing' => 'one'],
        'blank_line_after_opening_tag' => false,
        'declare_strict_types'         => true,
        'header_comment' => [
            'comment_type' => 'PHPDoc',
            'location'     => 'after_declare_strict',
            'header' => <<<HEADER
This file is part of CSVSpeaker, a PHP Experts, Inc., Project.

Copyright © 2019 PHP Experts, Inc.
Author: Theodore R. Smith <theodore@phpexperts.pro>
  GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
  https://www.phpexperts.pro/
  https://github.com/phpexpertsinc/CSVSpeaker

This file is licensed under the MIT License.
HEADER,
        ]
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('venodr')
            ->in(__DIR__)
    );
