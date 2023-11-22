<?php namespace TicTacToe\ChatGPT;

interface ChatGPTConversation
{
    public function addContext(string $message): void;
    public function say(string $message): Response;
    public function transcript(): Messages;
}