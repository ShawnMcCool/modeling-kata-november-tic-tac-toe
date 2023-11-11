<?php
namespace TicTacToe;

final class TurnOrder
{
    private function __construct(
        private PlayerNumber $currentPlayer
    ) {
    }

    public static function selectedRandomlyAtStart(): self
    {
        return new self(
            PlayerNumber::fromInt(random_int(1, 2))
        );
    }

    public function endTurn(): void
    {
        $this->currentPlayer = $this->nextPlayer();
    }
    
    public function currentPlayer(): PlayerNumber
    {
        return $this->currentPlayer;
    }

    public function nextPlayer(): PlayerNumber
    {
        return $this->currentPlayer->isFirst()
            ? PlayerNumber::second()
            : PlayerNumber::first();
    }
}