<?php

namespace TicTacToe\AIPlayers;

use Throwable;
use TicTacToe\ChatGPT\ChatGPTConversation;
use TicTacToe\ChatGPT\Response;
use TicTacToe\GamePlay\Events\GameEndedInATie;
use TicTacToe\GamePlay\Events\GameWasStarted;
use TicTacToe\GamePlay\Events\MarkWasPlaced;
use TicTacToe\GamePlay\Events\PlayerWonTheGame;
use TicTacToe\GamePlay\Game;
use TicTacToe\GamePlay\MarkPosition;
use TicTacToe\GamePlay\PlayerName;
use TicTacToe\Messaging\EventDispatcher;
use TicTacToe\Messaging\EventListener;

use function PhAnsi\cyan;
use function PhAnsi\red;

final class ChatGPTPlayer implements EventListener
{
    private readonly PlayerName $humanPlayer;

    public function __construct(
        private readonly Game $game,
        private readonly ChatGPTConversation $chatGPT,
        private readonly PlayerName $aiPlayer,
        private readonly EventDispatcher $dispatcher,
    ) {
    }

    public function handle($event): void
    {
        match ($event::class) {
            GameWasStarted::class => $this->gameWasStarted($event),
            MarkWasPlaced::class => $this->markWasPlaced($event),
            PlayerWonTheGame::class => $this->playerWonTheGame($event),
            GameEndedInATie::class => $this->gameEndedInATie($event),
        };
    }

    private function gameWasStarted(GameWasStarted $event): void
    {
        $this->chatGPT->addContext(
            'You are playing tic tac toe. The game is played on a 3x3 matrix. The coordinate of the top left position is 1,1. The coordinate of the bottom right position is 3,3. Your responses should always be 2d coordinates separated by a comma.'
        );

        if ($event->firstPlayer->equals($this->aiPlayer)) {
            $this->game->placeMark(
                $this->aiPlayer,
                $this->markFromResponse(
                    $this->chatGPT->say('You are going first, where do you place your mark?')
                )
            );
            
            $this->dispatcher->dispatchEvents(
                $this->game->flushEvents()
            );
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
            $this->game->placeMark(
                $this->aiPlayer,
                $this->markFromResponse(
                    $this->chatGPT->say(
                        "I played in the position {$event->markPosition->x()},{$event->markPosition->y()}. It's your turn, where do you play?."
                    )
                )
            );

            $this->dispatcher->dispatchEvents(
                $this->game->flushEvents()
            );
        } catch (Throwable $t) {
            echo red('Oopsie...') . " {$t->getMessage()}.\n";
            echo cyan('Chat GPT Transcript') . "\n";
            echo json_encode($this->chatGPT->transcript()->toApi(), JSON_PRETTY_PRINT);
            die();
        }
    }

    private function playerWonTheGame(PlayerWonTheGame $event): void
    {
    }

    private function gameEndedInATie(GameEndedInATie $event): void
    {
    }

    private function markFromResponse(Response $response): MarkPosition
    {
        [$x, $y] = $this->matchString('(\d)\s?,\s?(\d)', $response->message());

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
}