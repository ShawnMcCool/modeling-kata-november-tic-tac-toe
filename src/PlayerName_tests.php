<?php
namespace TicTacToe;

use function TicTacToe\Testing\expectEqual;
use function TicTacToe\Testing\expectFalse;
use function TicTacToe\Testing\expectTrue;
use function TicTacToe\Testing\it;

it('renders names as a string', function () {
    expectEqual(
        'Tippin',
        PlayerName::fromString('Tippin')->toString()
    );
});

it('determines if two names are the same', function () {
    expectTrue(
        PlayerName::fromString('Xander')->equals(
            PlayerName::fromString('Xander')
        )
    );

    expectFalse(
        PlayerName::fromString('Xander')->equals(
            PlayerName::fromString('Cherif')
        )
    );
});