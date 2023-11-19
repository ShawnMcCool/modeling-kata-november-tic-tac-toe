<?php namespace TicTacToe\GamePlay\Events;

use TicTacToe\GamePlay\MarkPosition;
use TicTacToe\GamePlay\PlayerName;

final readonly class MarkWasPlaced
{
    public function __construct(
        public PlayerName $playerName,
        public MarkPosition $markPosition,
    ) {}
}