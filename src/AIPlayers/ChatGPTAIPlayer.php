<?php namespace TicTacToe\AIPlayers;

use Throwable;
use TicTacToe\ChatGPT\ChatGPTConversation;
use TicTacToe\ChatGPT\Response;
use TicTacToe\GamePlay\Events\GameWasStarted;
use TicTacToe\GamePlay\Events\MarkWasPlaced;
use TicTacToe\GamePlay\Game;
use TicTacToe\GamePlay\MarkPosition;
use TicTacToe\GamePlay\PlayerName;
use TicTacToe\Messaging\EventDispatcher;
use TicTacToe\Messaging\EventListener;

use function PhAnsi\cyan;
use function PhAnsi\red;

final class ChatGPTAIPlayer implements EventListener
{
    public function __construct(
        private readonly Game $game,
        private readonly ChatGPTConversation $chatGPT,
        private readonly PlayerName $aiPlayer,
        private readonly EventDispatcher $dispatcher,
    ) {
    }

    public function handle($event): void
    {
        if ($event instanceof GameWasStarted) {
            $this->gameWasStarted($event);
        } elseif ($event instanceof MarkWasPlaced) {
            $this->markWasPlaced($event);
        }
    }

    private function gameWasStarted(GameWasStarted $event): void
    {
        $this->chatGPT->addContext(
            'You are playing standard tic tac toe on a 3x3 matrix. Respond in 2d coordinates with row first then column.'
        );

        if ($event->firstPlayer->equals($this->aiPlayer)) {
            $this->placeMark("It's your turn.");
        }
    }

    private function markWasPlaced(MarkWasPlaced $event): void
    {
        if ($this->game->isOver()) {
            return;
        }

        if ($event->playerName->equals($this->aiPlayer)) {
            return;
        }

        try {
            $this->placeMark(
                "I played at {$event->markPosition->x()},{$event->markPosition->y()}. It's your turn."
            );
        } catch (Throwable $t) {
            echo red('Oopsie...') . " {$t->getMessage()}.\n";
            echo cyan('Chat GPT Transcript') . "\n";
            echo json_encode($this->chatGPT->transcript()->toApi(), JSON_PRETTY_PRINT);
            die();
        }
    }

    private function markFromResponse(Response $response): MarkPosition
    {
        [$x, $y] = $this->matchString('(\d)\s?,\s?(\d)', $response->message());

        if (! $x || ! $y) {
            echo red("Oopies..") . " Chat GPT didn't respond with anything useful.";
            die();
        }
        return MarkPosition::fromCoordinates($x, $y);
    }

    private static function matchString(string $pattern, string $haystack): array
    {
        preg_match('/' . $pattern . '/', $haystack, $matches);

        if (empty($matches)) {
            return [];
        }

        return array_slice($matches, 1);
    }

    private function placeMark(string $message): void
    {
        $this->game->placeMark(
            $this->aiPlayer,
            $this->markFromResponse(
                $this->chatGPT->say($message)
            )
        );

        $this->dispatcher->dispatchEvents(
            $this->game->flushEvents()
        );
    }
}