<?php namespace TicTacToe\GamePlay;

use TicTacToe\GamePlay\Events\GameEndedInATie;
use TicTacToe\GamePlay\Events\GameWasStarted;
use TicTacToe\GamePlay\Events\MarkWasPlaced;
use TicTacToe\GamePlay\Events\PlayerWonTheGame;

it('can be started', function () {
    $game = Game::start(
        Players::named(
            'Shawn',
            'Xander'
        )
    );

    $events = $game->flushEvents();

    expectEqual(
        1,
        count($events)
    );

    /** @var GameWasStarted $event */
    $event = $events[0];

    expectTrue(
        $event instanceof GameWasStarted
    );

    expectTrue(
        $event->firstPlayerMark == 'X'
    );

    expectTrue(
        $event->secondPlayerMark == 'O'
    );

    if (
        $event->firstPlayer->equals(
            PlayerName::fromString('Shawn')
        )
    ) {
        expectTrue(
            $event->secondPlayer->equals(
                PlayerName::fromString('Xander')
            )
        );
    } else {
        expectTrue(
            $event->firstPlayer->equals(
                PlayerName::fromString('Xander')
            )
        );

        expectTrue(
            $event->secondPlayer->equals(
                PlayerName::fromString('Shawn')
            )
        );
    }
});

it('only allows the current player to place a mark', function () {
    $game = Game::start(
        Players::named(
            'Shawn',
            'Xander'
        )
    );

    /** @var GameWasStarted $startEvent */
    $startEvent = $game->flushEvents()[0];

    $wrongPlayer = $startEvent->firstPlayer->equals(PlayerName::fromString('Shawn'))
        ? 'Xander'
        : 'Shawn';

    expectException(
        InvalidPlay::class,
        fn () => $game->placeMark(
            PlayerName::fromString($wrongPlayer),
            MarkPosition::fromCoordinates(1, 1)
        )
    );

    $game->placeMark(
        $startEvent->firstPlayer,
        MarkPosition::fromCoordinates(1, 1)
    );

    /** @var MarkWasPlaced $markEvent */
    $markEvent = $game->flushEvents()[0];

    expectTrue(
        $markEvent->playerName->equals(
            $startEvent->firstPlayer
        )
    );

    expectTrue(
        $markEvent->markPosition->equals(
            MarkPosition::fromCoordinates(1, 1)
        )
    );
});

it('does not support placing a mark on a game that is over', function () {
    /*
     * start the game
     */
    $game = Game::start(
        Players::named(1, 2)
    );

    /** @var GameWasStarted $startEvent */
    $startEvent = $game->flushEvents()[0];

    $game->placeMark(
        $startEvent->firstPlayer,
        MarkPosition::fromCoordinates(1, 1)
    );

    $game->placeMark(
        $startEvent->secondPlayer,
        MarkPosition::fromCoordinates(1, 2)
    );

    $game->placeMark(
        $startEvent->firstPlayer,
        MarkPosition::fromCoordinates(2, 1)
    );

    $game->placeMark(
        $startEvent->secondPlayer,
        MarkPosition::fromCoordinates(2, 2)
    );

    $game->placeMark(
        $startEvent->firstPlayer,
        MarkPosition::fromCoordinates(3, 1)
    );

    /*
     * game should be over, because player 1 won
     */
    expectException(
        InvalidPlay::class,
        fn () => $game->placeMark(
            $startEvent->secondPlayer,
            MarkPosition::fromCoordinates(3, 2)
        )
    );
});

it('can be won', function () {
    /*
     * start the game
     */
    $game = Game::start(
        Players::named(1, 2)
    );

    /** @var GameWasStarted $startEvent */
    $startEvent = $game->flushEvents()[0];

    $game->placeMark(
        $startEvent->firstPlayer,
        MarkPosition::fromCoordinates(1, 1)
    );

    $game->placeMark(
        $startEvent->secondPlayer,
        MarkPosition::fromCoordinates(1, 2)
    );

    $game->placeMark(
        $startEvent->firstPlayer,
        MarkPosition::fromCoordinates(2, 1)
    );

    $game->placeMark(
        $startEvent->secondPlayer,
        MarkPosition::fromCoordinates(2, 2)
    );

    $game->placeMark(
        $startEvent->firstPlayer,
        MarkPosition::fromCoordinates(3, 1)
    );
    
    $events = $game->flushEvents();
    
    /** @var array<PlayerWonTheGame> $playerWonEvents */
    $playerWonEvents = array_filter(
        $events,
        fn($event) => $event instanceof PlayerWonTheGame
    );
    
    expectTrue(
        ! empty($playerWonEvents)
    );
    
    $playerWonEvent = current($playerWonEvents);
    
    expectTrue(
        $playerWonEvent instanceof PlayerWonTheGame
    );
    
    expectTrue(
        $playerWonEvent->playerName->equals(
            $startEvent->firstPlayer
        )
    );
});

it('can result in a tie', function () {
    /*
     * O X X
     * X X O
     * O O X
     */
    $game = Game::start(
        Players::named(1, 2)
    );

    /** @var GameWasStarted $startEvent */
    $startEvent = $game->flushEvents()[0];

    $game->placeMark(
        $startEvent->firstPlayer,
        MarkPosition::fromCoordinates(2, 1)
    );

    $game->placeMark(
        $startEvent->secondPlayer,
        MarkPosition::fromCoordinates(1, 1)
    );

    $game->placeMark(
        $startEvent->firstPlayer,
        MarkPosition::fromCoordinates(3, 1)
    );

    $game->placeMark(
        $startEvent->secondPlayer,
        MarkPosition::fromCoordinates(3, 2)
    );

    $game->placeMark(
        $startEvent->firstPlayer,
        MarkPosition::fromCoordinates(1, 2)
    );
    
    $game->placeMark(
        $startEvent->secondPlayer,
        MarkPosition::fromCoordinates(1, 3)
    );

    $game->placeMark(
        $startEvent->firstPlayer,
        MarkPosition::fromCoordinates(2, 2)
    );
    
    $game->placeMark(
        $startEvent->secondPlayer,
        MarkPosition::fromCoordinates(2, 3)
    );

    $game->placeMark(
        $startEvent->firstPlayer,
        MarkPosition::fromCoordinates(3, 3)
    );
    
    $events = $game->flushEvents();
    
    $tieEvents = array_filter(
        $events,
        fn($event) => $event instanceof GameEndedInATie
    );
    
    expectTrue(
        ! empty($tieEvents)
    );
});