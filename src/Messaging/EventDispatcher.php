<?php namespace TicTacToe\Messaging;

final class EventDispatcher
{
    public function __construct(
        private array $listeners = []
    ) {
    }

    public function subscribe($listener): void
    {
        $this->listeners[] = $listener;
    }

    public function dispatchEvents(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatchEvent($event);
        }
    }

    public function dispatchEvent($event): void
    {
        foreach ($this->listeners as $listener) {
            $listener->handle($event);
        }
    }
}