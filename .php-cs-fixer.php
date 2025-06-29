<?php

declare(strict_types=1);

use CoiSA\PhpCsFixer\PhpCsFixer;

$paths = [
    __FILE__,
    __DIR__,
];

$header = file_get_contents(__DIR__ . '/.docheader');

return PhpCsFixer::create($paths, $header);
