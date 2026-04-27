<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/frontend',
        __DIR__ . '/backend',
        __DIR__ . '/console',
        __DIR__ . '/common',
    ])
    ->withSkip([
        __DIR__ . '/**/runtime/*',
    ])
    ->withParallel(360)
    ->withImportNames()
    ->withPhpSets(php85: true)
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);
