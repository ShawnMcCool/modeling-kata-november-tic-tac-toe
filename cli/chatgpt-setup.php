<?php

use TicTacToe\UserInterface\PlayerInput;

use function PhAnsi\green;

require 'vendor/autoload.php';

echo "This script should be run in the root directory of the repository. If it is not, it will not work.\n\n";

$apiKey = PlayerInput::chatGPTApiKey();

file_put_contents('.chatgpt_apikey', trim(trim($apiKey), '"'));

echo green("\nAPI key written to .chatgpt_apikey\n");