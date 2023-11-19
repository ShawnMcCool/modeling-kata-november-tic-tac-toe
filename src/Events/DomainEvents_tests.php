<?php namespace TicTacToe\Events;

use function TicTacToe\Testing\expectEqual;
use function TicTacToe\Testing\it;

it('flushes all recorded events at once', function() {
    
    $events = DomainEvents::empty();
    
    $events->record('event one');
    $events->record('event two');
    
    $flushedEvents = $events->flush();
    
    expectEqual(
        2, count($flushedEvents)
    );
    
    expectEqual(
        'event one', $flushedEvents[0]
    );
    
    expectEqual(
        'event two', $flushedEvents[1]
    );
});

it('is empty after a flush', function() {
    
    $events = DomainEvents::empty();
    
    $events->record('event one');
    $events->record('event two');
    
    $events->flush();
    
    expectEqual(
        [], $events->flush()
    );
});