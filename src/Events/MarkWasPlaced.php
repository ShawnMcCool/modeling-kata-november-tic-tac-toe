<?php namespace TicTacToe\Events;

use TicTacToe\MarkPosition;
use TicTacToe\PlayerName;

final readonly class MarkWasPlaced
{
    public function __construct(
        public PlayerName $playerName,
        public MarkPosition $markPosition,
    ) {}
}