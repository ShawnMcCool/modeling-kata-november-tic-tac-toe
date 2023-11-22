<?php

namespace TicTacToe\UserInterface;

use PhAnsi\Decoration\TextTable;
use TicTacToe\GamePlay\Events\GameEndedInATie;
use TicTacToe\GamePlay\Events\GameWasStarted;
use TicTacToe\GamePlay\Events\MarkWasPlaced;
use TicTacToe\GamePlay\Events\PlayerWonTheGame;
use TicTacToe\GamePlay\MarkPosition;
use TicTacToe\GamePlay\PlayerName;
use TicTacToe\Messaging\EventListener;

use function PhAnsi\green;
use function PhAnsi\red;

final class RenderPlayerFeedback implements EventListener
{
    private array $board =
        [
            [1, '', '', ''],
            [2, '', '', ''],
            [3, '', '', ''],
        ];

    private array $playerMarks = [];

    public function handle($event): void
    {
        match ($event::class) {
            GameWasStarted::class => $this->gameWasStarted($event),
            MarkWasPlaced::class => $this->markWasPlaced($event),
            PlayerWonTheGame::class => $this->playerWonTheGame($event),
            GameEndedInATie::class => $this->gameEndedInATie($event),
        };
    }

    private function gameWasStarted(GameWasStarted $event): void
    {
        echo "\n";

        $this->playerMarks = [
            $event->firstPlayer->toString() => $event->firstPlayerMark,
            $event->secondPlayer->toString() => $event->secondPlayerMark,
        ];

        $this->drawBoard();

        echo "{$event->firstPlayer->toString()} is the first player and is placing '$event->firstPlayerMark'.\n";
        echo "{$event->secondPlayer->toString()} is the first player and is placing '$event->secondPlayerMark'.\n";
        echo "\n";
    }

    private function markWasPlaced(MarkWasPlaced $event): void
    {
        $this->placeMark(
            $event->playerName,
            $event->markPosition,
        );
        
        $this->drawBoard();
        echo "{$event->playerName->toString()} has placed an {$this->playerMarks[$event->playerName->toString()]} at {$event->markPosition->x()},{$event->markPosition->y()}.\n";
    }

    private function playerWonTheGame(PlayerWonTheGame $event): void
    {
        echo "\n";
        echo green("Congratulations!") . " {$event->playerName->toString()} has won the game!\n\n";
    }

    private function gameEndedInATie(GameEndedInATie $event): void
    {
        echo red("Oh no!") . " the game ended without a winner!\n\n";
    }

    private function drawBoard(): void
    {
        echo TextTable::make()
            ->withTitle("Tic Tac Toe")
            ->withHeaders('', 1, 2, 3)
            ->withRows($this->board)
            ->toString();
    }

    private function placeMark(
        PlayerName $player, 
        MarkPosition $position
    ): void {
        $this->board[$position->y()-1][$position->x()] = $this->playerMarks[$player->toString()];
    }
}