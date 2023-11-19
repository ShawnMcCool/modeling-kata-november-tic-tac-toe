<?php namespace TicTacToe;

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
            throw InvalidPlayers::playerNamesMustBeUnique();
        }

        return new self([
            PlayerName::fromString($firstPlayerName),
            PlayerName::fromString($secondPlayerName),
        ]);
    }

    public function withIndex(int $playerIndex): PlayerName
    {
        return $this->playerNames[$playerIndex];
    }
}