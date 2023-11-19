<?php namespace TicTacToe\Events;

final class DomainEvents
{
    private function __construct(
        private array $events,
    ) {}
    
    public static function empty(): self
    {
        return new self([]);
    }

    public function record($event): void
    {
        $this->events[] = $event;
    }

    public function flush(): array
    {
        $eventsToFlush = $this->events;
        $this->events = [];
        return $eventsToFlush;
    }
}