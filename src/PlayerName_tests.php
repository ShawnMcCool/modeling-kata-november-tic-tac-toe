<?php namespace TicTacToe;

it('has a name', function() {
    expectEqual(
        'Tippin',
        PlayerName::fromString('Tippin')->toString()
    );
});