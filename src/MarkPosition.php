<?php
namespace TicTacToe;

final readonly class MarkPosition
{
    private function __construct(
        private int $x,
        private int $y
    ) {
    }

    public static function fromCoordinates(
        int $x,
        int $y
    ): self {
        if (
            $x < 1
            || $x > 3
            || $y < 1
            || $y > 3
        ) {
            throw MarkIsInvalid::invalidCoordinates($x, $y);
        }

        return new self($x, $y);
    }

    public function equals(self $that): bool
    {
        return $this->x == $that->x
            && $this->y == $that->y;
    }

    public function x(): int
    {
        return $this->x;
    }

    public function y(): int
    {
        return $this->y;
    }
}