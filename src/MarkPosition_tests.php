<?php namespace TicTacToe;

use function TicTacToe\Testing\expectEqual;
use function TicTacToe\Testing\expectException;
use function TicTacToe\Testing\expectFalse;
use function TicTacToe\Testing\expectTrue;
use function TicTacToe\Testing\it;

it('expects X/Y coordinates between 1 and 3', function() {
    
    expectException(
        MarkIsInvalid::class,
        fn() => MarkPosition::fromCoordinates(0, 1)
    );
    
    expectException(
        MarkIsInvalid::class,
        fn() => MarkPosition::fromCoordinates(4, 0)
    );
    
    expectException(
        MarkIsInvalid::class,
        fn() => MarkPosition::fromCoordinates(1, 0)
    );
    
    expectException(
        MarkIsInvalid::class,
        fn() => MarkPosition::fromCoordinates(1, 4)
    );
    
    expectTrue(
        MarkPosition::fromCoordinates(1, 2) instanceof MarkPosition
    );
});

it('compares positions for equality', function() {
    
    expectTrue(
        MarkPosition::fromCoordinates(1, 1)->equals(
            MarkPosition::fromCoordinates(1, 1)
        )
    );
    
    expectFalse(
        MarkPosition::fromCoordinates(1, 1)->equals(
            MarkPosition::fromCoordinates(2, 1)
        )
    );
});

it('exposes X, Y coordinates as integers', function() {
    
    expectEqual(
        1, MarkPosition::fromCoordinates(1, 2)->x()
    );
    
    expectEqual(
        3, MarkPosition::fromCoordinates(3, 2)->x()
    );
    
    expectEqual(
        1, MarkPosition::fromCoordinates(2, 1)->y()
    );
    
    expectEqual(
        2, MarkPosition::fromCoordinates(3, 2)->y()
    );
});