<?php

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
[$playerOneName, $playerTwoName] = PlayerInput::humanPlayerNames();
    
$game = Game::start(
    Players::named(
        $playerOneName,
        $playerTwoName,
    )
);

$dispatcher->dispatchEvents(
    $game->flushEvents()
);

while ( ! $game->isOver()) {
    [$name, $x, $y] = PlayerInput::versusHumanPlayerMarkPlacementSelection();
    
    try {
        $game->placeMark(
            PlayerName::fromString($name),
            MarkPosition::fromCoordinates($x, $y)
        );
    } catch (Throwable $t) {
        echo red('Oopsie...') . " {$t->getMessage()}.\n";
    }
    
    $dispatcher->dispatchEvents(
        $game->flushEvents()
    );
}