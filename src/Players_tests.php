<?php

namespace TicTacToe;

use function TicTacToe\Testing\expectException;
use function TicTacToe\Testing\expectTrue;
use function TicTacToe\Testing\it;

it('references players by index', function () {
    $players = Players::named(
        'Codito',
        'Damian'
    );

    expectTrue(
        $players->withIndex(0)->equals(
            PlayerName::fromString('Codito')
        )
    );

    expectTrue(
        $players->withIndex(1)->equals(
            PlayerName::fromString('Damian')
        )
    );
});

it('requires that players have unique names', function () {

    expectException(
        InvalidPlayers::class,
        fn() => Players::named(
            'Nicolai',
            'Nicolai'
        )
    );
});