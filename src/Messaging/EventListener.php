<?php namespace TicTacToe\Messaging;

interface EventListener
{
    public function handle($event): void;
}