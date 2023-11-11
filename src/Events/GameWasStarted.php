<?php
namespace TicTacToe\Events;


final readonly class GameWasStarted
{
    public function __construct(
        public string $firstPlayerName,
        public string $firstPlayerMark,
        public string $secondPlayerName,
        public string $secondPlayerMark,
    ) {}
    
}