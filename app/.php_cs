<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
;

return PhpCsFixer\Config::create()
    ->setLineEnding("\r\n")
    ->setRiskyAllowed(true)
    ->setRules([
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'ordered_class_elements' => true,
        'psr4' => true,
    ])
    ->setFinder($finder)
;
