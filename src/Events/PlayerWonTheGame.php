<?php namespace TicTacToe\Events;

use TicTacToe\PlayerName;

final readonly class PlayerWonTheGame
{
    public function __construct(
        public PlayerName $playerName
    ) {}
}