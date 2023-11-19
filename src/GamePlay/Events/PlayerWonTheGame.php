<?php namespace TicTacToe\GamePlay\Events;

use TicTacToe\GamePlay\PlayerName;

final readonly class PlayerWonTheGame
{
    public function __construct(
        public PlayerName $playerName
    ) {}
}