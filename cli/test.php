<?php

require 'vendor/autoload.php';

$testFiles = TicTacToe\Testing\find_in_path('src', '_tests.php');

foreach ($testFiles as $testFile) {
    require($testFile);
}