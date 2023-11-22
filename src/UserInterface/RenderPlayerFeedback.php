<?php namespace TicTacToe\UserInterface;

use PhAnsi\Decoration\TextTable;
use TicTacToe\GamePlay\Events\GameEndedInATie;
use TicTacToe\GamePlay\Events\GameWasStarted;
use TicTacToe\GamePlay\Events\MarkWasPlaced;
use TicTacToe\GamePlay\Events\PlayerWonTheGame;
use TicTacToe\GamePlay\MarkPosition;
use TicTacToe\GamePlay\PlayerName;
use TicTacToe\Messaging\EventListener;

use function PhAnsi\brightWhite;
use function PhAnsi\green;
use function PhAnsi\magenta;
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

        $this->colorByMark(
            $event->firstPlayer,
            "{$event->firstPlayer->toString()} is the first player and is placing '$event->firstPlayerMark'.\n"
        );
        
        $this->colorByMark(
            $event->secondPlayer,
            "{$event->secondPlayer->toString()} is the second player and is placing '$event->secondPlayerMark'.\n"
        );

        echo "\n";
    }

    private function markWasPlaced(MarkWasPlaced $event): void
    {
        $this->placeMark(
            $event->playerName,
            $event->markPosition,
        );

        $this->drawBoard();
        
        echo $this->colorByMark(
            $event->playerName,
            "{$event->playerName->toString()} has placed an {$this->playerMarks[$event->playerName->toString()]} at {$event->markPosition->x()},{$event->markPosition->y()}.\n\n"
        );
    }

    private function playerWonTheGame(PlayerWonTheGame $event): void
    {
        $winningPlayer = $this->colorByMark(
            $event->playerName,
            $event->playerName->toString()
        );
        
        echo green("Congratulations!") . " $winningPlayer has won the game!\n\n";
    }

    private function gameEndedInATie(GameEndedInATie $event): void
    {
        echo red("Oh no!") . " the game ended without a winner!\n\n";
    }

    private function drawBoard(): void
    {
        echo "\n"
            . TextTable::make()
                ->withTitle(brightWhite("Tic Tac Toe"))
                ->withHeaders('', 1, 2, 3)
                ->withRows($this->board)
                ->toString();
    }

    private function placeMark(
        PlayerName $player,
        MarkPosition $position
    ): void {
        $mark = $this->playerMarks[$player->toString()];

        $this->board[$position->y() - 1][$position->x()] = $this->colorByMark($player, $mark);
    }

    private function colorByMark(
        PlayerName $player,
        string $message
    ): string {
        $mark = $this->playerMarks[$player->toString()];

        return match ($mark) {
            'X' => green($message),
            'O' => magenta($message),
        };
    }
}