<?php

require 'src/Testing/test_bootstrap.php';

function findInPath(
    string $path,
    string $suffix,
): array {
    $directoryIterator = new RecursiveDirectoryIterator($path);
    $iterator = new RecursiveIteratorIterator($directoryIterator);
    $files = [];

    foreach ($iterator as $file) {
        if ($file->isFile() && str_ends_with($file, $suffix)) {
            $files[] = $file->getPathname();
        }
    }

    return $files;
}

foreach (findInPath('src', '_tests.php') as $testFile) {
    require($testFile);
}