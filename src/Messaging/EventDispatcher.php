<?php namespace TicTacToe\Messaging;

final class EventDispatcher
{
    private array $queue = [];
    
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
            $this->queue[] = $event;
        }
        
        while ( ! empty($this->queue)) {
            $event = array_shift($this->queue);
            
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