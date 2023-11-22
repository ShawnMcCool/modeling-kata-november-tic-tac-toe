<?php

use TicTacToe\ChatGPT\Messages;
use TicTacToe\GamePlay\Game;
use TicTacToe\GamePlay\MarkPosition;
use TicTacToe\GamePlay\PlayerName;
use TicTacToe\GamePlay\Players;
use TicTacToe\Messaging\EventDispatcher;
use TicTacToe\UserInterface\PlayerInput;
use TicTacToe\UserInterface\RenderPlayerFeedback;

use function PhAnsi\red;

require 'vendor/autoload.php';

$dispatcher = new EventDispatcher;
$dispatcher->subscribe(new RenderPlayerFeedback());

echo "\n";
[$humanName] = PlayerInput::humanPlayerName();

$game = Game::start(
    Players::named(
        $humanName,
        "Random",
    )
);

$messages = new Messages();
$dispatcher->subscribe(
    new TicTacToe\AIPlayers\RandomAIPlayer(
        $game,
        PlayerName::fromString("Random"),
        $dispatcher,
    )
);

$dispatcher->dispatchEvents(
    $game->flushEvents()
);

while ( ! $game->isOver()) {
    [$x, $y] = PlayerInput::versusAiPlayerMarkPlacementSelection();

    try {
        $game->placeMark(
            PlayerName::fromString($humanName),
            MarkPosition::fromCoordinates($x, $y)
        );
    } catch (Throwable $t) {
        echo red("Oopsie...") . " {$t->getMessage()}.\n";
    }

    $dispatcher->dispatchEvents(
        $game->flushEvents()
    );
}