<?php namespace TicTacToe\ChatGPT;

interface ChatGPTConversation
{
    public function addSystemMessage(string $message): void;
    public function say(string $message): Response;
}