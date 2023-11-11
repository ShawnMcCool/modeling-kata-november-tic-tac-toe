<?php namespace TicTacToe;

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

    public function toString(): string
    {
        return $this->name;
    }
}