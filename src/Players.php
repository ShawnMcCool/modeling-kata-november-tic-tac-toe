<?php

namespace TicTacToe;

use InvalidArgumentException;

final readonly class Players
{
    private function __construct(
        private array $playerNames,
    ) {
    }

    public static function named(
        string $firstPlayerName,
        string $secondPlayerName,
    ): self {
        if ($firstPlayerName == $secondPlayerName) {
            throw new InvalidArgumentException("Player names must be unique. Sorry.");
        }

        return new self([
            PlayerName::fromString($firstPlayerName),
            PlayerName::fromString($secondPlayerName),
        ]);
    }

    public function nameFor(PlayerNumber $playerNumber): string
    {
        return $this->playerNames[$playerNumber->toInt()]->toString();
    }

    public function markFor(PlayerNumber $playerNumber): string
    {
        return $playerNumber->isFirst() ? 'X' : 'O';
    }
}