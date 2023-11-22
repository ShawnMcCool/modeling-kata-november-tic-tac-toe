<?php

use TicTacToe\ChatGPT\CurlChatGPTConversation;
use TicTacToe\ChatGPT\Messages;

use function PhAnsi\cyan;
use function PhAnsi\green;
use function PhAnsi\red;

require 'vendor/autoload.php';

$convo = new CurlChatGPTConversation(
    'https://api.openai.com/v1/chat/completions',
    file_get_contents('.chatgpt_apikey'),
    new Messages()
);

$convo->addContext('You are ensuring a dubious software engineer that you are functioning correctly.');

try {
    $response = $convo->say("Is this application's connection to OpenAPI working correctly?");
    echo cyan("Is this application's connection to OpenAPI working correctly?\n");
    echo green($response->message());
    echo "\n";
} catch (Throwable $t) {
    echo red("Oopsie... {$t->getMessage()}");
    echo "\n";
}