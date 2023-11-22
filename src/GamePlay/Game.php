<?php namespace TicTacToe\GamePlay;

use TicTacToe\Messaging\DomainEvents;
use TicTacToe\GamePlay\Events\GameEndedInATie;
use TicTacToe\GamePlay\Events\GameWasStarted;
use TicTacToe\GamePlay\Events\MarkWasPlaced;
use TicTacToe\GamePlay\Events\PlayerWonTheGame;

final class Game
{
    private bool $gameIsOver = false;

    private function __construct(
        private readonly Matrix $matrix,
        private readonly TurnOrder $turnOrder,
        private readonly Players $players,
        private readonly DomainEvents $events,
    ) {
    }

    public static function start(
        Players $players
    ): self {
        $turnOrder = TurnOrder::selectFirstPlayerRandomly($players);

        $game = new self(
            Matrix::empty(),
            $turnOrder,
            $players,
            DomainEvents::empty(),
        );

        $game->events->record(
            new GameWasStarted(
                $turnOrder->currentPlayer(), 'X',
                $turnOrder->nextPlayer(), 'O',
            )
        );

        return $game;
    }

    public function placeMark(
        PlayerName $player,
        MarkPosition $markPosition
    ): void {
        if ($this->gameIsOver) {
            throw InvalidPlay::gameIsOver();
        }
        
        if ( ! $this->turnOrder->currentPlayerIs($player)) {
            throw InvalidPlay::playerIsPlayingOutOfTurnOrder($player, $this->turnOrder->currentPlayer());
        }

        $this->matrix->placeMark($markPosition, $player);
        $this->events->record(
            new MarkWasPlaced($player, $markPosition)
        );

        $winningPlayer = $this->matrix->winningPlayer();
        if ($winningPlayer) {
            $this->events->record(
                new PlayerWonTheGame($winningPlayer)
            );
            $this->gameIsOver = true;
            return;
        }

        if ($this->matrix->isFull()) {
            $this->events->record(
                new GameEndedInATie()
            );
            $this->gameIsOver = true;
            return;
        }

        // change turn
        $this->turnOrder->endTurn();
    }

    public function flushEvents(): array
    {
        return $this->events->flush();
    }

    public function isOver(): bool
    {
        return $this->gameIsOver;
    }
}