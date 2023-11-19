<?php namespace TicTacToe\GamePlay;

use DomainException;

final class InvalidPlay extends DomainException
{
    public static function gameIsOver(): self
    {
        return new self(
            "You can't play when the game is over, bro."
        );
    }

    public static function playerIsPlayingOutOfTurnOrder(
        PlayerName $player,
        PlayerName $currentPlayer
    ): self {
        return new self(
            "It looks like {$player->toString()} is trying to play on {$currentPlayer->toString()}'s turn."
        );
    }

    public static function markPositionIsNotAvailable(
        PlayerName $player,
        MarkPosition $markPosition
    ): self {
        return new self(
            "It looks like {$player->toString()} tried to play at position {$markPosition->x()}, {$markPosition->y()} but that spot was already taken." 
        );
    }
}