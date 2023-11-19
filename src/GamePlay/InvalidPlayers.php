<?php namespace TicTacToe\GamePlay;

use InvalidArgumentException;

final class InvalidPlayers extends InvalidArgumentException
{
    public static function playerNamesMustBeUnique(): self
    {
        return new self(
            "Player names must be unique."
        );
    }
}