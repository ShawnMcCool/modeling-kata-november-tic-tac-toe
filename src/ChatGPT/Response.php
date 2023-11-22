<?php namespace TicTacToe\ChatGPT;

use UnexpectedValueException;

final class Response
{
    private function __construct(
        private readonly string $message,
    ) {}
    
    public static function fromApi($responseBody): self
    {
        $response = json_decode($responseBody);
        
        if (empty($response->choices)) {
            throw new UnexpectedValueException("No choices were returned from ChatGPT.");
        }
        
        $choice = current($response->choices);

        return new Response($choice->message->content);
    }

    public function message(): string
    {
        return $this->message;
    }
}