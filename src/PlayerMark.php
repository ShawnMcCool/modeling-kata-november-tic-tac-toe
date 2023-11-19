<?php namespace TicTacToe;

final readonly class PlayerMark
{
    private function __construct(
        private PlayerName $player,
        private MarkPosition $markPosition,
    ) {}
    
    public static function atPosition(
        PlayerName $player,
        MarkPosition $markPosition
    ): self {
        return new self($player, $markPosition);
    }

    public function isOnRow(int $row): bool
    {
        return $this->markPosition->y() == $row;
    }
    
    public function isOnColumn(int $column): bool
    {
        return $this->markPosition->x() == $column;
    }
    
    public function isAtPosition(
        MarkPosition $markPosition
    ): bool {
        return $this->markPosition->equals($markPosition);
    }

    public function player(): PlayerName
    {
        return $this->player;
    }
}