<?php namespace TicTacToe\GamePlay;

use DomainException;

final class MarkIsInvalid extends DomainException
{
    public static function invalidCoordinates(int $x, int $y): self
    {
        return new self(
            "Specified mark coordinates, ($x, $y) are invalid. X and Y must each be within the range of 1-3."
        );
    }
}