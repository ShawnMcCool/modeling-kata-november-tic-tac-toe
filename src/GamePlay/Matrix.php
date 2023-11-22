<?php namespace TicTacToe\GamePlay;

final class Matrix
{
    private function __construct(
        private array $playerMarks,
    ) {}
    

    public static function empty(): self
    {
        return new self([]);
    }

    public function positionIsAvailable(
        MarkPosition $markPosition
    ): bool {
        $marksAtPosition = array_filter(
            $this->playerMarks,
            fn (PlayerMark $existingMark) => $existingMark->isAtPosition($markPosition)
        );

        return empty($marksAtPosition);
    }

    public function placeMark(
        MarkPosition $markPosition,
        PlayerName $player
    ): void {
        if ( ! $this->positionIsAvailable($markPosition)) {
            throw InvalidPlay::markPositionIsNotAvailable($player, $markPosition);
        }

        $this->playerMarks[] = PlayerMark::atPosition($player, $markPosition);
    }

    public function winningPlayer(): ?PlayerName
    {
        return $this->winnerForRows()
            ?? $this->winnerForColumns()
            ?? $this->winnerForDiagonals();
    }

    private function winnerForRows(): ?PlayerName
    {
        foreach (range(1, 3) as $row) {
            /** @var array<PlayerMark> $marksOnRow */
            $marksOnRow = array_filter(
                $this->playerMarks,
                fn (PlayerMark $playerMark) => $playerMark->isOnRow($row)
            );

            if ($this->isAWinningSet($marksOnRow)) {
                return current($marksOnRow)->player();
            }
        }

        return null;
    }

    private function winnerForColumns(): ?PlayerName
    {
        foreach (range(1, 3) as $column) {
            /** @var array<PlayerMark> $marksOnColumn */
            $marksOnColumn = array_filter(
                $this->playerMarks,
                fn (PlayerMark $playerMark) => $playerMark->isOnColumn($column)
            );

            if ($this->isAWinningSet($marksOnColumn)) {
                return current($marksOnColumn)->player();
            }
        }

        return null;
    }

    private function winnerForDiagonals(): ?PlayerName
    {
        /*
         * Test upper-left to lower-right.
         */
        /** @var array<PlayerMark> $marks */
        $marks = array_filter(
            $this->playerMarks,
            fn (PlayerMark $playerMark) => $playerMark->isAtPosition(MarkPosition::fromCoordinates(1, 1))
            || $playerMark->isAtPosition(MarkPosition::fromCoordinates(2, 2))
            || $playerMark->isAtPosition(MarkPosition::fromCoordinates(3, 3))
        );

        if ($this->isAWinningSet($marks)) {
            return $marks[0]->player();
        }
        
        /*
         * Test upper-right to lower-left
         */
        /** @var array<PlayerMark> $marks */
        $marks = array_filter(
            $this->playerMarks,
            fn (PlayerMark $playerMark) => $playerMark->isAtPosition(MarkPosition::fromCoordinates(3, 1))
            || $playerMark->isAtPosition(MarkPosition::fromCoordinates(2, 2))
            || $playerMark->isAtPosition(MarkPosition::fromCoordinates(1, 3))
        );

        if ($this->isAWinningSet($marks)) {
            return current($marks)->player();
        }

        return null;
    }

    private function isAWinningSet(array $marks): bool
    {
        if (
            count($marks) == 3
            && $this->allThreeMarksWereMadeByTheSamePlayer($marks)
        ) {
            return true;
        }

        return false;
    }

    private function allThreeMarksWereMadeByTheSamePlayer(array $marks): bool
    {
        $marks = array_values($marks);
        
        return $marks[0]->player()->equals($marks[1]->player())
            && $marks[0]->player()->equals($marks[2]->player());
    }

    public function isFull(): bool
    {
        return count($this->playerMarks) == 9;
    }
}