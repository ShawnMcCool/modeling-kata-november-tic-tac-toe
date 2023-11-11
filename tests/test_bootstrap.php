<?php

namespace Tests;

use Throwable;

require 'vendor/autoload.php';

# The most incredibly naive and hardcoded testing framework ever made.

function it(string $description, callable $test): void
{
    $exceptions = [];

    try {
        $test();
    } catch (Throwable $exception) {
        $exceptions[] = $exception;
    }

    if (empty($exceptions)) {
        $filename = basename(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[0]['file']);
        echo test_description_display($filename, $description);
        return;
    }

    if ($exceptions) {
        echo "\n";

        foreach ($exceptions as $exception) {
            // naive method to find the most relevant trace frame
            $traceFrames = array_filter(
                $exception->getTrace(),
                fn ($frame) => $frame['file'] != __FILE__ && basename($frame['file']) !== 'run.php'
            );
            $frame = reset($traceFrames);

            // which file, which test
            $testFile = basename(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[0]['file']);
            echo test_description_display($testFile, $description);

            // display the exception
            echo exception_display(
                $testFile,
                basename($frame['file']),
                $frame['line'],
                $exception::class,
                $exception->getMessage()
            );
        }

        echo "\n";
    }
}

function expectException(string $exceptionClass, callable $test): void
{
    try {
        $test();
    } catch (\Exception $exception) {
        // the victory condition
        if (get_class($exception) === $exceptionClass) {
            return;
        }

        throw new InvalidAssertion(
            "Expected exception '{$exceptionClass}' but received '" . get_class($exception) . "'."
        );
    }

    throw new InvalidAssertion("Expected exception '{$exceptionClass}' but received no exception.");
}

function expectEqual(mixed $expected, mixed $actual): void
{
    if ($expected === $actual) {
        return;
    }

    throw new InvalidAssertion(
        "Expected " . var_export($expected, true) . " but got " . var_export($actual, true) . "."
    );
}

function expectTrue(bool $expected): void
{
    if ($expected === true) {
        return;
    }

    throw new InvalidAssertion("Expected value to be 'true' but got 'false'.");
}

function expectFalse(bool $expected)
{
    if ($expected === false) {
        return;
    }

    throw new InvalidAssertion("Expected value to be 'false' but got 'true'.");
}

function test_description_display(
    string $filename,
    string $description
): string {
    return "$filename :: it {$description}\n";
}

function exception_display(
    string $testFile,
    string $exceptionThrowInFile,
    string $thrownInFileLineNumber,
    string $exceptionClass,
    string $exceptionMessage
): string {
    return str_pad('', strlen($testFile) + 4, ' ', STR_PAD_LEFT)
        . "$exceptionThrowInFile : $thrownInFileLineNumber - $exceptionClass - $exceptionMessage\n";
}