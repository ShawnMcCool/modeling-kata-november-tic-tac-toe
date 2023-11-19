<?php namespace TicTacToe\GamePlay\Events;

use TicTacToe\GamePlay\PlayerName;

final readonly class GameWasStarted
{
    public function __construct(
        public PlayerName $firstPlayer,
        public string $firstPlayerMark,
        public PlayerName $secondPlayer,
        public string $secondPlayerMark,
    ) {}
}