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
        "ChatGPT One",
        "ChatGPT Two",
    )
);

$messagesOne = new Messages();
$dispatcher->subscribe(
    new TicTacToe\AIPlayers\ChatGPTAIPlayer(
        $game,
        new CurlChatGPTConversation(
            'https://api.openai.com/v1/chat/completions',
            file_get_contents('.chatgpt_apikey'),
            $messagesOne,
        ),
        PlayerName::fromString("ChatGPT One"),
        $dispatcher,
    )
);

$messagesTwo = new Messages();
$dispatcher->subscribe(
    new TicTacToe\AIPlayers\ChatGPTAIPlayer(
        $game,
        new CurlChatGPTConversation(
            'https://api.openai.com/v1/chat/completions',
            file_get_contents('.chatgpt_apikey'),
            $messagesTwo,
        ),
        PlayerName::fromString("ChatGPT Two"),
        $dispatcher,
    )
);

try {
    $dispatcher->dispatchEvents(
        $game->flushEvents()
    );
} catch (Throwable $t) {
    echo red("Oopsie..") . " {$t->getMessage()}.\n";

    echo cyan('ChatGPT One Transcript') . "\n";
    echo json_encode($messagesOne->toApi(), JSON_PRETTY_PRINT);
    echo "\n";

    echo cyan('ChatGPT Two Transcript') . "\n";
    echo json_encode($messagesTwo->toApi(), JSON_PRETTY_PRINT);
    echo "\n";
}
