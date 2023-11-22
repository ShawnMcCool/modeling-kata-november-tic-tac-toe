<?php

namespace TicTacToe\AIPlayers;

use TicTacToe\GamePlay\Events\GameWasStarted;
use TicTacToe\GamePlay\Events\MarkWasPlaced;
use TicTacToe\GamePlay\Game;
use TicTacToe\GamePlay\MarkPosition;
use TicTacToe\GamePlay\PlayerMark;
use TicTacToe\GamePlay\PlayerName;
use TicTacToe\Messaging\EventDispatcher;
use TicTacToe\Messaging\EventListener;

use function PhAnsi\cyan;

final class SkilledAIPlayer implements EventListener
{
    private array $playerMarks = [];
    private array $remainingMarkPositions = [];

    public function __construct(
        private readonly Game $game,
        private readonly PlayerName $otherPlayer,
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
            foreach (range(1, 3) as $y) {
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

        $this->playerMarks[] = PlayerMark::atPosition(
            $event->playerName,
            $event->markPosition,
        );
        $this->removeRemainingMark($event->markPosition);

        $this->placeMark();
    }

    private function placeMark(): void
    {
        $this->game->placeMark(
            $this->aiPlayer,
            $this->selectAMarkPosition(),
        );

        $this->dispatcher->dispatchEvents(
            $this->game->flushEvents()
        );
    }

    private function selectAMarkPosition(): MarkPosition
    {
        /*
         * if first, choose a corner
         */
        if (empty($this->playerMarks)) {
            echo cyan("AI: I'm playing the corner as my first move.\n");
            return $this->reservePlayerMark(
                PlayerMark::atPosition(
                    $this->aiPlayer,
                    MarkPosition::fromCoordinates(1, 1)
                )
            );
        }

        /*
         * if second...
         */
        if (count($this->playerMarks) == 1) {
            /*
             * if a corner is marked
             */
            $cornerIsMarked = ! is_null(
                $this->firstMarkMatching(
                    $this->cornerMarkPositions()
                )
            );

            if ($cornerIsMarked) {
                /*
                 * mark the center
                 */
                echo cyan("AI: I'm playing the center as my first move.\n");
                $this->playerMarks[] = PlayerMark::atPosition(
                    $this->aiPlayer,
                    MarkPosition::fromCoordinates(2, 2)
                );

                return MarkPosition::fromCoordinates(2, 2);
            } else {
                /*
                 * otherwise target a corner
                 */
                echo cyan("AI: I'm playing the corner as my first move.\n");
                $availableCorner = $this->firstEmptySpotMatching(
                    $this->cornerMarkPositions()
                );

                $this->playerMarks[] = PlayerMark::atPosition(
                    $this->aiPlayer,
                    $availableCorner
                );

                return $availableCorner;
            }
        }

        /*
         * if we are going to win, choose that spot
         */
        $potentiallyWinningSpot = $this->winningSpotFor($this->aiPlayer);
        if ($potentiallyWinningSpot) {
            echo cyan("AI: I'm going for the win.\n");
            $this->playerMarks[] = PlayerMark::atPosition(
                $this->aiPlayer,
                $potentiallyWinningSpot
            );

            return $potentiallyWinningSpot;
        }

        /*
         * if they're going to win, choose that spot
         */
        $potentiallyWinningSpot = $this->winningSpotFor($this->otherPlayer);
        if ($potentiallyWinningSpot) {
            echo cyan("AI: I'm blocking you.\n");
            $this->playerMarks[] = PlayerMark::atPosition(
                $this->aiPlayer,
                $potentiallyWinningSpot
            );

            return $potentiallyWinningSpot;
        }

        // if we have a corner and there's an opposite corner
        $targetedOppositeCorner = $this->targetedOppositeCornerFor($this->aiPlayer);
        if($targetedOppositeCorner) {
            $this->playerMarks[] = PlayerMark::atPosition(
                $this->aiPlayer,
                $targetedOppositeCorner
            );

            return $targetedOppositeCorner;

        }


        // if we have 2 corners, go for a third

        // random remaining position
        echo cyan("AI: I'm playing a random position.\n");
        $randomPosition = $this->randomRemainingPosition();
        $this->playerMarks[] = PlayerMark::atPosition(
            $this->aiPlayer,
            $randomPosition
        );
        return $randomPosition;
    }

    private function cornerMarkPositions(): array
    {
        return [
            MarkPosition::fromCoordinates(1, 1),
            MarkPosition::fromCoordinates(3, 1),
            MarkPosition::fromCoordinates(1, 3),
            MarkPosition::fromCoordinates(3, 3),
        ];
    }

    private function firstMarkMatching(array $marks): ?PlayerMark
    {
        foreach ($marks as $mark) {
            $found = array_filter(
                $this->playerMarks,
                fn (PlayerMark $position) => $position->isAtPosition($mark)
            );

            if ( ! empty($found)) {
                return current($found);
            }
        }

        return null;
    }

    private function firstEmptySpotMatching(array $marks): ?MarkPosition
    {
        foreach ($marks as $mark) {
            $found = array_filter(
                $this->playerMarks,
                fn (PlayerMark $position) => $position->isAtPosition($mark)
            );

            if (empty($found)) {
                return $mark;
            }
        }

        return null;
    }

    private function winningSpotFor(PlayerName $player): ?MarkPosition
    {
        // if row 1-3 all belong to player
        foreach (range(1, 3) as $row) {
            $winningSpotInRow = $this->winningSpotInRowFor($row, $player);
            if ($winningSpotInRow) {
                return $winningSpotInRow;
            }
        }

        // if col 1-3 all belong to player
        foreach (range(1, 3) as $col) {
            $winningSpotInCol = $this->winningSpotInColFor($col, $player);
            if ($winningSpotInCol) {
                return $winningSpotInCol;
            }
        }

        // if diagonals belong to player
        $winningSpotInDiagonalOne = $this->winningSpotInDiagonalOneFor($player);
        if ($winningSpotInDiagonalOne) {
            return $winningSpotInDiagonalOne;
        }

        $winningSpotInDiagonalTwo = $this->winningSpotInDiagonalTwoFor($player);
        if ($winningSpotInDiagonalTwo) {
            return $winningSpotInDiagonalTwo;
        }

        return null;
    }

    private function winningSpotInRowFor(
        int $row,
        PlayerName $player
    ): ?MarkPosition {
        // 2 marks only
        $marksOnRow = array_filter(
            $this->playerMarks,
            fn (PlayerMark $playerMark) => $playerMark->isOnRow($row)
        );

        if (count($marksOnRow) != 2) {
            return null;
        }

        // all marks owned by player
        $marksOwnedByPlayer = array_filter(
            $marksOnRow,
            fn (PlayerMark $playerMark) => $playerMark->player()->equals($player)
        );

        if (count($marksOwnedByPlayer) != 2) {
            return null;
        }

        foreach (range(1, 3) as $col) {
            $found = false;

            /** @var PlayerMark $mark */
            foreach ($marksOnRow as $mark) {
                if ($mark->isOnColumn($col)) {
                    $found = true;
                }
            }

            if ( ! $found) {
                return MarkPosition::fromCoordinates($col, $row);
            }
        }

        return null;
    }

    private function winningSpotInColFor(
        int $col,
        PlayerName $player
    ): ?MarkPosition {
        // 2 marks only
        $marksOnCol = array_filter(
            $this->playerMarks,
            fn (PlayerMark $playerMark) => $playerMark->isOnColumn($col)
        );

        if (count($marksOnCol) != 2) {
            return null;
        }

        // all marks owned by player
        $marksOwnedByPlayer = array_filter(
            $marksOnCol,
            fn (PlayerMark $playerMark) => $playerMark->player()->equals($player)
        );

        if (count($marksOwnedByPlayer) != 2) {
            return null;
        }

        foreach (range(1, 3) as $row) {
            $found = false;

            /** @var PlayerMark $mark */
            foreach ($marksOnCol as $mark) {
                if ($mark->isOnRow($row)) {
                    $found = true;
                }
            }

            if ( ! $found) {
                return MarkPosition::fromCoordinates($col, $row);
            }
        }

        return null;
    }

    private function winningSpotInDiagonalOneFor(PlayerName $player): ?MarkPosition
    {
        // 2 marks only
        $marksOnDiag = $this->allMarksMatching(
            MarkPosition::fromCoordinates(1, 1),
            MarkPosition::fromCoordinates(2, 2),
            MarkPosition::fromCoordinates(3, 3),
        );

        if (count($marksOnDiag) != 2) {
            return null;
        }

        // all owned by player
        $marksOwnedByPlayer = array_filter(
            $marksOnDiag,
            fn (PlayerMark $playerMark) => $playerMark->player()->equals($player)
        );

        if (count($marksOwnedByPlayer) != 2) {
            return null;
        }

        // missing space
        return $this->firstEmptySpotMatching([
            MarkPosition::fromCoordinates(1, 1),
            MarkPosition::fromCoordinates(2, 2),
            MarkPosition::fromCoordinates(3, 3),
        ]);
    }

    private function winningSpotInDiagonalTwoFor(PlayerName $player): ?MarkPosition
    {
        // 2 marks only
        $marksOnDiag = $this->allMarksMatching(
            MarkPosition::fromCoordinates(3, 1),
            MarkPosition::fromCoordinates(2, 2),
            MarkPosition::fromCoordinates(1, 3),
        );

        if (count($marksOnDiag) != 2) {
            return null;
        }

        // all owned by player
        $marksOwnedByPlayer = array_filter(
            $marksOnDiag,
            fn (PlayerMark $playerMark) => $playerMark->player()->equals($player)
        );

        if (count($marksOwnedByPlayer) != 2) {
            return null;
        }

        // missing space
        return $this->firstEmptySpotMatching([
            MarkPosition::fromCoordinates(3, 1),
            MarkPosition::fromCoordinates(2, 2),
            MarkPosition::fromCoordinates(1, 3),
        ]);
    }

    private function allMarksMatching(
        MarkPosition ...$positions
    ): array {
        $marks = [];

        foreach ($positions as $position) {
            $marks[] = $this->firstMarkMatching([$position]);
        }

        return array_filter($marks);
    }

    private function randomRemainingPosition(): MarkPosition
    {
        shuffle($this->remainingMarkPositions);
        $this->remainingMarkPositions = array_values($this->remainingMarkPositions);
        return array_shift($this->remainingMarkPositions);
    }

    private function reservePlayerMark(PlayerMark $playerMark): MarkPosition
    {
        $this->playerMarks[] = $playerMark;

        $this->removeRemainingMark($playerMark->position());

        return $playerMark->position();
    }

    private function removeRemainingMark(MarkPosition $position): void
    {
        $this->remainingMarkPositions = array_filter(
            $this->remainingMarkPositions,
            fn (MarkPosition $markPosition) => ! $position->equals($markPosition)
        );
    }

    private function targetedOppositeCornerFor(PlayerName $player): ?MarkPosition
    {
        $cornerMarks = $this->allMarksMatching(
            ...$this->cornerMarkPositions()
        );

        if (count($cornerMarks) == 4) {
            return null;
        }

        $cornerMarksOwnedByPlayer = array_filter(
            $cornerMarks,
            fn (PlayerMark $playerMark) => $playerMark->player()->equals($player)
        );


        /** @var PlayerMark $cornerMark */
        foreach ($cornerMarksOwnedByPlayer as $cornerMark) {
            $oppositeCornerMark = PlayerMark::atPosition(
                $cornerMark->player(),
                MarkPosition::fromCoordinates(
                    $cornerMark->position()->x() == 3 ? 1 : 3,
                    $cornerMark->position()->y() == 3 ? 1 : 3,
                )
            );

            if ($oppositeCornerMark && $this->firstEmptySpotMatching([$oppositeCornerMark->position()])) {
                echo cyan("AI: I'm targeting the opposite corner.");

                $this->playerMarks[] = $oppositeCornerMark;

                $this->removeRemainingMark($oppositeCornerMark->position());
                
                return $oppositeCornerMark->position();
            }
        }
        
        return null;
    }
}