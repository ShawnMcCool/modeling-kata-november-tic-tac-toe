<?php

use TicTacToe\ChatGPT\CurlChatGPTConversation;
use TicTacToe\ChatGPT\Messages;
use TicTacToe\GamePlay\Game;
use TicTacToe\GamePlay\MarkPosition;
use TicTacToe\GamePlay\PlayerName;
use TicTacToe\GamePlay\Players;
use TicTacToe\Messaging\EventDispatcher;
use TicTacToe\UserInterface\PlayerInput;
use TicTacToe\UserInterface\RenderPlayerFeedback;

use function PhAnsi\cyan;
use function PhAnsi\red;

require 'vendor/autoload.php';

$dispatcher = new EventDispatcher;
$dispatcher->subscribe(new RenderPlayerFeedback());

echo "\n";
[$humanName, $aiName] = PlayerInput::humanAndAiPlayerNames();

$game = Game::start(
    Players::named(
        $humanName,
        $aiName,
    )
);

$messages = new Messages();
$dispatcher->subscribe(
    new TicTacToe\AIPlayers\ChatGPTPlayer(
        $game,
        new CurlChatGPTConversation(
            'https://api.openai.com/v1/chat/completions',
            file_get_contents('.chatgpt_apikey'),
            $messages,
        ),
        PlayerName::fromString($aiName),
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

echo cyan('Chat GPT Transcript') . "\n";
echo json_encode($messages->toApi(), JSON_PRETTY_PRINT);
echo "\n";