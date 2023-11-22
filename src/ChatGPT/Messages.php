<?php namespace TicTacToe\ChatGPT;

final class Messages
{
    private array $messages = [];
    
    public function addSystem(string $message): void
    {
        $this->messages[] = [
            'role' => 'system',
            'content' => $message,
        ];
    }
    
    public function addUser(string $message): void
    {
        $this->messages[] = [
            'role' => 'user',
            'content' => $message,
        ];
    }

    public function toApi(): array
    {
        return $this->messages;
    }
}