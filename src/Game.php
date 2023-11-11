<?php

namespace TicTacToe;

use TicTacToe\Events\GameWasStarted;

final class Game
{
    private readonly DomainEvents $events;

    private function __construct(
        private readonly Players $players,
        private readonly TurnOrder $turnOrder,
        private readonly Matrix $matrix,
    ) {
        $this->events = DomainEvents::empty();
    }

    public static function start(
        Players $players,
        TurnOrder $turnOrder,
        Matrix $matrix,
    ): self {
        $game = new self(
            $players,
            $turnOrder,
            $matrix
        );

        $game->events->record(
            new GameWasStarted(
                $players->nameFor($turnOrder->currentPlayer()),
                $players->markFor($turnOrder->currentPlayer()),
                $players->nameFor($turnOrder->nextPlayer()),
                $players->markFor($turnOrder->nextPlayer()),
            )
        );

        return $game;
    }
}