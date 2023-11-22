<?php

use TicTacToe\ChatGPT\CurlChatGPTConversation;
use TicTacToe\ChatGPT\Messages;
use TicTacToe\GamePlay\Game;
use TicTacToe\GamePlay\PlayerName;
use TicTacToe\GamePlay\Players;
use TicTacToe\Messaging\EventDispatcher;
use TicTacToe\UserInterface\RenderPlayerFeedback;

use function PhAnsi\cyan;
use function PhAnsi\red;

require 'vendor/autoload.php';

$dispatcher = new EventDispatcher;
$dispatcher->subscribe(new RenderPlayerFeedback());

$game = Game::start(
    Players::named(
        "ChatGPT",
        "Skilled",
    )
);

$messages = new Messages();
$dispatcher->subscribe(
    new TicTacToe\AIPlayers\ChatGPTAIPlayer(
        $game,
        new CurlChatGPTConversation(
            'https://api.openai.com/v1/chat/completions',
            file_get_contents('.chatgpt_apikey'),
            $messages,
        ),
        PlayerName::fromString("ChatGPT"),
        $dispatcher,
    )
);

$dispatcher->subscribe(
    new TicTacToe\AIPlayers\SkilledAIPlayer(
        $game,
        PlayerName::fromString("ChatGPT"),
        PlayerName::fromString("Skilled"),
        $dispatcher,
    )
);

try {
    $dispatcher->dispatchEvents(
        $game->flushEvents()
    );
} catch (Throwable $t) {
    echo red("Oopsie..") . " {$t->getMessage()}.\n";

    echo cyan('ChatGPT Transcript') . "\n";
    echo json_encode($messages->toApi(), JSON_PRETTY_PRINT);
    echo "\n";
}
