<?php namespace TicTacToe;

final class TurnOrder
{
    private function __construct(
        private readonly Players $players,
        private int $currentPlayer,
    ) {
    }

    public static function selectFirstPlayerRandomly(
        Players $players
    ): self {
        return new self(
            $players,
            random_int(0, 1),
        );
    }
    
    public function currentPlayerIs(PlayerName $player): bool
    {
        return $this->currentPlayer()->equals($player);
    }

    public function currentPlayer(): PlayerName
    {
        return $this->players->withIndex($this->currentPlayer);
    }

    public function nextPlayer(): PlayerName
    {
        return $this->players->withIndex($this->nextPlayerIndex());
    }

    public function endTurn(): void
    {
        $this->currentPlayer = $this->nextPlayerIndex();
    }

    private function nextPlayerIndex(): int
    {
        return ($this->currentPlayer + 1) % 2;
    }
}