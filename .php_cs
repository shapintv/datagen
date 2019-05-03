
<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->exclude(['tests/app/var', 'tests/app/cache'])
    ->files()
    ->name('*.php')
;

return PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules([
        '@Symfony' => true,
        'declare_strict_types' => true,
    ])
;
