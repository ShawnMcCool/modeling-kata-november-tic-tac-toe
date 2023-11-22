<?php namespace TicTacToe\UserInterface;

final class PlayerInput
{
    public static function playerNames(): array
    {
        return [
            implode(' ', self::read('(\S*)', "First Player Name:")),
            implode(' ', self::read('(\S*)', "Second Player Name:")),
        ];
    }

    public static function playerMarkPlacementSelection(): array
    {
        return self::read(
            '(\S*) (\d)\s?,\s?(\d)', "Enter your move: [Format: Name 1,1]: " 
        );
    }

    public static function chatGPTApiKey(): string
    {
        return implode(' ', self::read('(\S*)', "Your OpenAPI API Key:"));
    }

    private static function read(
        string $pattern,
        string $prompt,
    ): array {
        while (true) {
            $input = self::matchString(
                $pattern,
                trim(readline("$prompt "))
            );

            if ( ! $input) {
                echo "Error: invalid entry\n";
                continue;
            }
            
            return $input;
        }
    }

    private static function matchString(string $pattern, string $haystack): array
    {
        preg_match('/' . $pattern . '/', $haystack, $matches);

        if (empty($matches)) {
            return [];
        }

        return array_slice($matches, 1);
    }
}