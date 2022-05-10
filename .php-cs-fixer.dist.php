<?php

$finder = (new PhpCsFixer\Finder())
    ->in([__DIR__.'/src']);

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setCacheFile('.php-cs-fixer.cache')
    ->setRules([
        '@Symfony'                   => true,
        'declare_strict_types'       => false,
        'binary_operator_spaces'     => [
            'operators' => [
                '=>' => 'align',
                '='  => 'align',
            ],
        ],
        'braces'                     => ['allow_single_line_closure' => true],
        'native_function_invocation' => [
            'scope' => 'namespaced',
        ],
        'phpdoc_summary'             => false,
        'no_superfluous_phpdoc_tags' => true,
        'no_unused_imports'          => false,
    ]);
