<?php namespace TicTacToe\UserInterface;

final class PlayerInput
{
    private static function matchString(string $pattern, string $haystack): array
    {
        preg_match('/' . $pattern . '/', $haystack, $matches);

        if (empty($matches)) {
            return [];
        }

        return array_slice($matches, 1);
    }

    public static function playerTilePlacementSelection(): array
    {
        while (true) {
            $input = static::matchString(
                '(\S*) (\d):(\d) (\S*)',
                trim(readline("\n\"Bob 5:1 right\": "))
            );

            if ( ! $input) {
                echo "Error: invalid entry\n";
                continue;
            }

            return $input;
        }
    }
}