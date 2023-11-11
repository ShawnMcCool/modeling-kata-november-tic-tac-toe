<?php

use TicTacToe\Testing\InvalidAssertion;

it('identifies failing assertions', function () {
    expectException(
        InvalidAssertion::class,
        fn () => expectTrue(false)
    );

    expectException(
        InvalidAssertion::class,
        fn () => expectFalse(true)
    );

    expectException(
        InvalidAssertion::class,
        fn () => expectEqual(123, 0)
    );
});

it('identifies passing assertions', function () {
    expectEqual(true, true);
    expectTrue(true);
    expectFalse(false);
});

it('identifies unexpected exceptions', function () {
    expectException(
        InvalidAssertion::class,
        fn () => expectException(
            InvalidAssertion::class,
            fn () => throw new Exception('oops')
        )
    );
});

it('identifies expected exceptions', function () {
    expectException(
        Exception::class,
        fn () => throw new Exception('expected')
    );
});