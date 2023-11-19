<?php namespace TicTacToe\GamePlay;

it('determines if the specified player is the current player', function () {
    $turnOrder = TurnOrder::selectFirstPlayerRandomly(
        Players::named(
            'Doeke',
            'Paul'
        )
    );

    if ($turnOrder->currentPlayerIs(PlayerName::fromString('Doeke'))) {
        expectTrue(
            $turnOrder->nextPlayer()->equals(
                PlayerName::fromString('Paul')
            )
        );
    } else {
        expectTrue(
            $turnOrder->nextPlayer()->equals(
                PlayerName::fromString('Doeke')
            )
        );
    }
});

it('determines the next player', function () {
    $turnOrder = TurnOrder::selectFirstPlayerRandomly(
        Players::named(
            'Doeke',
            'Paul'
        )
    );

    if (
        $turnOrder->currentPlayer()->equals(
            PlayerName::fromString('Doeke')
        )
    ) {
        expectTrue(
            $turnOrder->nextPlayer()->equals(
                PlayerName::fromString('Paul')
            )
        );
    } else {
        expectTrue(
            $turnOrder->nextPlayer()->equals(
                PlayerName::fromString('Doeke')
            )
        );
    }
});

it('changes the current player when the turn ends', function () {
    $turnOrder = TurnOrder::selectFirstPlayerRandomly(
        Players::named(
            'Doeke',
            'Paul'
        )
    );

    if (
        $turnOrder->currentPlayer()->equals(
            PlayerName::fromString('Doeke')
        )
    ) {
        $turnOrder->endTurn();

        expectTrue(
            $turnOrder->currentPlayerIs(
                PlayerName::fromString('Paul')
            )
        );
    } else {
        $turnOrder->endTurn();
        
        expectTrue(
            $turnOrder->currentPlayerIs(
                PlayerName::fromString('Doeke')
            )
        );
    }
});