<?php namespace TicTacToe;


use InvalidArgumentException;

final readonly class PlayerNumber
{
    private function __construct(
        private int $number,
    ) {}

    public static function first(): self
    {
        return new self(1);
    }
    
    public static function second(): self
    {
        return new self(2);
    }
    
    public static function fromInt(int $playerNumber): self
    {
        if ( ! in_array($playerNumber, [1, 2])) {
            throw new InvalidArgumentException("$playerNumber is not a valid player number. Try 1 or 2.");
        }

        return new self($playerNumber);
    }

    public function isFirst(): bool
    {
        return $this->number == 1;
    }

    public function toInt(): int
    {
        return $this->number;
    }
}