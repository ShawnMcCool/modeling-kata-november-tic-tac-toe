<?php namespace TicTacToe\GamePlay;

it('ensures that only one mark can be made per position', function () {
    $matrix = Matrix::empty();

    $matrix->placeMark(
        MarkPosition::fromCoordinates(1, 1),
        PlayerName::fromString('Dave')
    );

    expectException(
        InvalidPlay::class,
        fn () => $matrix->placeMark(
            MarkPosition::fromCoordinates(1, 1),
            PlayerName::fromString('Mateus')
        )
    );
});

it('determines that a player wins when they cover a full row', function () {
    $matrix = Matrix::empty();

    $matrix->placeMark(
        MarkPosition::fromCoordinates(1, 1),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(2, 1),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(3, 1),
        PlayerName::fromString('Dave')
    );

    expectTrue(
        $matrix->winningPlayer()?->equals(
            PlayerName::fromString('Dave')
        )
    );
});

it('requires three marks by the same player to win a row', function () {
    $matrix = Matrix::empty();

    $matrix->placeMark(
        MarkPosition::fromCoordinates(1, 1),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(2, 1),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(3, 1),
        PlayerName::fromString('Andrew')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );
});

it('determines that a player wins when they cover a full column', function () {
    $matrix = Matrix::empty();

    $matrix->placeMark(
        MarkPosition::fromCoordinates(1, 1),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(1, 2),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(1, 3),
        PlayerName::fromString('Dave')
    );

    expectTrue(
        $matrix->winningPlayer()?->equals(
            PlayerName::fromString('Dave')
        )
    );
});

it('requires three marks by the same player to win a column', function () {
    $matrix = Matrix::empty();

    $matrix->placeMark(
        MarkPosition::fromCoordinates(1, 1),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(1, 2),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(1, 3),
        PlayerName::fromString('Andrew')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );
});

it('determines that a player wins when they cover the diagonal from upper-left to lower-right', function () {
    $matrix = Matrix::empty();

    $matrix->placeMark(
        MarkPosition::fromCoordinates(1, 1),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(2, 2),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(3, 3),
        PlayerName::fromString('Dave')
    );

    expectTrue(
        $matrix->winningPlayer()?->equals(
            PlayerName::fromString('Dave')
        )
    );
});

it('requires three marks by the same player to win the diagonal from upper-left to lower-right', function () {
    $matrix = Matrix::empty();

    $matrix->placeMark(
        MarkPosition::fromCoordinates(1, 1),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(2, 2),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(3, 3),
        PlayerName::fromString('Andrew')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );
});

it('determines that a player wins when they cover the diagonal from upper-right to lower-left', function () {
    $matrix = Matrix::empty();

    $matrix->placeMark(
        MarkPosition::fromCoordinates(3, 1),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(2, 2),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(1, 3),
        PlayerName::fromString('Dave')
    );

    expectTrue(
        $matrix->winningPlayer()?->equals(
            PlayerName::fromString('Dave')
        )
    );
});

it('requires three marks by the same player to win the diagonal from upper-right to lower-left', function () {
    $matrix = Matrix::empty();

    $matrix->placeMark(
        MarkPosition::fromCoordinates(3, 1),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(2, 2),
        PlayerName::fromString('Dave')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );

    $matrix->placeMark(
        MarkPosition::fromCoordinates(1, 3),
        PlayerName::fromString('Andrew')
    );

    expectFalse(
        (bool)$matrix->winningPlayer()
    );
});

it('knows if the matrix is fully covered', function () {
    $matrix = Matrix::empty();

    expectFalse(
        $matrix->isFull()
    );
    
    foreach (range(1, 3) as $row) {
        foreach (range(1, 3) as $column) {
            $matrix->placeMark(
                MarkPosition::fromCoordinates($column, $row),
                PlayerName::fromString('Dave')
            );
        }
    }
    
    expectTrue(
        $matrix->isFull()
    );
});