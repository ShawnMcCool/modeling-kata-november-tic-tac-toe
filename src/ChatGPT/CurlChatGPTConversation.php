<?php namespace TicTacToe\ChatGPT;

use Throwable;

use function PhAnsi\red;

final readonly class CurlChatGPTConversation implements ChatGPTConversation
{
    public function __construct(
        private string $apiEndpoint,
        private string $apiKey,
        private Messages $messages,
    ) {
    }

    public function addContext(
        string $message
    ): void {
        $this->messages->addSystem($message);
    }

    public function say(
        string $message
    ): Response {
        $this->messages->addUser($message);

        return $this->makeRequest();
    }

    public function transcript(): Messages
    {
        return $this->messages;
    }

    private function makeRequest(): Response
    {
        $ch = curl_init($this->apiEndpoint);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postData());
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json'
        ]);
        
        $response = curl_exec($ch);
        
        curl_close($ch);
    
        $response = Response::fromApi($response);
    
        $this->messages->addAssistant($response->message());
        
        return $response;
    }

    private function postData(): string
    {
        return json_encode([
            'model' => 'gpt-3.5-turbo',
            'messages' => $this->messages->toApi(),
            'temperature' => 0.7,
            'max_tokens' => 25,
        ]);
    }
}