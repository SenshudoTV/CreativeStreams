<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . DIRECTORY_SEPARATOR . 'app',
        __DIR__ . DIRECTORY_SEPARATOR . 'config',
        __DIR__ . DIRECTORY_SEPARATOR . 'database',
        __DIR__ . DIRECTORY_SEPARATOR . 'routes',
        __DIR__ . DIRECTORY_SEPARATOR . 'tests',
    ])
    ->append(['.php_cs'])
    ->notName('*.blade.php');

$rules = [
    '@Symfony'                          => true,
    'phpdoc_no_empty_return'            => false,
    'array_syntax'                      => ['syntax'  => 'short'],
    'yoda_style'                        => false,
    'binary_operator_spaces'            => [
        'operators' => [
            '=>' => 'align',
            '='  => 'align',
        ],
    ],
    'concat_space'                      => ['spacing' => 'one'],
    'increment_style'                   => ['style'   => 'post'],
    'not_operator_with_successor_space' => true,
];

return (new Config())
    ->setUsingCache(false)
    ->setRules($rules)
    ->setFinder($finder);
