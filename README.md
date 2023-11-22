# Tic Tac Toe

A design for the November 2023 modeling kata.

## Requirements

- The game is played on a grid that's 3 squares by 3 squares.
- The game is played by 2 players.
- One player is assigned X and the other is assigned O.
- Players take turns putting their marks in empty squares.
- The first player to get 3 of her marks in a row (up, down, across, or diagonally) is the winner.
- When all 9 squares are full, the game is over. If no player has 3 marks in a row, the game ends in a tie.

## Assumptions / Decisions Made

- My goal is to make something really easy to read and understand.
- I decided not to customize player counts, matrix sizes, etc.
- Players are named, because I like that.
- Player names are unique identifiers. I don't want to properly deal with identity.
- Player order is randomized on game start.
- First mark played is always X.
- Event dispatch is sequential, so no concern for messages out of order or duplicated.

## Behaviors

`php ./cli/test.php` to run tests.  

`php ./cli/play-against-human.php` to play an interactive 2 player game.  

`php ./cli/chatgpt-setup.php` to set up ChatGPT integration.  

`php ./cli/chatgpt-test.php` to test ChatGPT integration.  

`php ./cli/play-against-ai.php` to play against ChatGPT.