<?php

require 'vendor/autoload.php';

$testFiles = find_in_path('src', '_tests.php');

foreach ($testFiles as $testFile) {
    require($testFile);
}