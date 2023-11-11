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
