<?php namespace TicTacToe\AIPlayers;

use TicTacToe\GamePlay\Events\GameWasStarted;
use TicTacToe\GamePlay\Events\MarkWasPlaced;
use TicTacToe\GamePlay\Game;
use TicTacToe\GamePlay\MarkPosition;
use TicTacToe\GamePlay\PlayerName;
use TicTacToe\Messaging\EventDispatcher;
use TicTacToe\Messaging\EventListener;

final class RandomAIPlayer implements EventListener
{
    private array $remainingMarkPositions = [];

    public function __construct(
        private readonly Game $game,
        private readonly PlayerName $aiPlayer,
        private readonly EventDispatcher $dispatcher,
    ) {
    }

    public function handle($event): void
    {
        if ($event instanceof GameWasStarted) {
            $this->gameWasStarted($event);
        } elseif ($event instanceof MarkWasPlaced) {
            $this->markWasPlaced($event);
        }
    }

    private function gameWasStarted(GameWasStarted $event): void
    {
        foreach (range(1, 3) as $x) {
            foreach (range(1,3) as $y) {
                $this->remainingMarkPositions[] = MarkPosition::fromCoordinates($x, $y);
            }
        }
        
        if ($event->firstPlayer->equals($this->aiPlayer)) {
            $this->placeMark();
        }
    }

    private function markWasPlaced(MarkWasPlaced $event): void
    {
        if ($this->game->isOver()) {
            return;
        }

        if ($event->playerName->equals($this->aiPlayer)) {
            return;
        }
        
        $this->remainingMarkPositions = array_filter(
            $this->remainingMarkPositions,
            fn(MarkPosition $position) => ! $position->equals($event->markPosition)
        );

        $this->placeMark();
    }

    private function placeMark(): void
    {
        $this->game->placeMark(
            $this->aiPlayer,
            $this->randomMark(),
        );

        $this->dispatcher->dispatchEvents(
            $this->game->flushEvents()
        );
    }

    private function randomMark(): MarkPosition
    {
        shuffle($this->remainingMarkPositions);
        return array_shift($this->remainingMarkPositions);
    }
}