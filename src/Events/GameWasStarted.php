<?php namespace TicTacToe\Events;

use TicTacToe\PlayerName;

final readonly class GameWasStarted
{
    public function __construct(
        public PlayerName $firstPlayer,
        public string $firstPlayerMark,
        public PlayerName $secondPlayer,
        public string $secondPlayerMark,
    ) {
    }
}