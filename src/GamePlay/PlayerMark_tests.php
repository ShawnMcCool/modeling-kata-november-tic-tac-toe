<?php namespace TicTacToe\GamePlay;

it('determines if the mark was made by a specific player', function () {
    
    $mark = PlayerMark::atPosition(
        PlayerName::fromString('Muhammed'),
        MarkPosition::fromCoordinates(1, 1)
    );

    expectTrue(
        $mark->player()->equals(
            PlayerName::fromString('Muhammed')
        )
    );
});

it('determines if the mark was made at a specific position', function () {
    
    $mark = PlayerMark::atPosition(
        PlayerName::fromString('Muhammed'),
        MarkPosition::fromCoordinates(1, 1)
    );

    expectTrue(
        $mark->isAtPosition(
            MarkPosition::fromCoordinates(1, 1)
        )
    );
});

it('determines if the mark was made on a specific row', function () {
    
    $mark = PlayerMark::atPosition(
        PlayerName::fromString('Muhammed'),
        MarkPosition::fromCoordinates(3, 1)
    );

    expectTrue(
        $mark->isOnRow(1)
    );
    
    expectFalse(
        $mark->isOnRow(2)
    );
});

it('determines if the mark was made on a specific column', function () {
    
    $mark = PlayerMark::atPosition(
        PlayerName::fromString('Muhammed'),
        MarkPosition::fromCoordinates(1, 2)
    );

    expectTrue(
        $mark->isOnColumn(1)
    );
    
    expectFalse(
        $mark->isOnColumn(3)
    );
});