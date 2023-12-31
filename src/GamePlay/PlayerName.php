<?php namespace TicTacToe\GamePlay;

final readonly class PlayerName
{
    private function __construct(
        private string $name
    ) {}

    public static function fromString(
        string $name
    ): self {
        return new self($name);
    }

    public function equals(self $that): bool
    {
        return $this->name == $that->name;
    }
    
    public function toString(): string
    {
        return $this->name;
    }
}