<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TwilioController extends Controller
{
    private $twilio;
    private $from;

    public function __construct()
    {
        $sid = config("twilio.api_keys.sid");
        $token = config("twilio.api_keys.token");
        $this->twilio = new Client($sid, $token);
        $this->from = "whatsapp:" . config("twilio.api_keys.from", "+14155238886");
    }

    public function handleIncomingMessage(Request $request)
    {
        $from = $request->input('From');
        $body = $request->input('Body');

        Log::info("📥 Incoming WhatsApp Message from {$from}: {$body}");

        $response = $this->processUserResponse($body);
        $this->sendWhatsAppMessage($from, $response);

        return response('OK', 200);
    }

    private function sendWhatsAppMessage($to, $body)
    {
        try {
            $this->twilio->messages->create($to, [
                "from" => "whatsapp:+14155238886",
                "body" => $body,
            ]);
            Log::info("✅ WhatsApp message sent to $to: $body");
        } catch (\Exception $e) {
            Log::error("❌ Twilio Send Error: " . $e->getMessage());
        }

        return true;
    }

    private function askChatGPT($message)
    {
        try {
            Log::info('🔑 OpenRouter Key: ' . config('services.openai.key'));
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.key'),
                'HTTP-Referer' => 'https://webreca.com/', // Required
                'X-Title' => 'chatbot', // Optional but recommended
                'Content-Type' => 'application/json',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'mistralai/mixtral-8x7b', // Or another supported model
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $message],
                ],
            ]);

            if ($response->failed()) {
                Log::error("❌ OpenRouter API failed: " . $response->body());
                return "👋 Hi Udisha , How can I help you today?";
            }

            $data = $response->json();
            $chatReply = $data['choices'][0]['message']['content'] ?? 'No response from OpenRouter.';

            Log::info("🤖 OpenRouter Response: {$chatReply}");
            return $chatReply;
        } catch (\Exception $e) {
            Log::error("❌ OpenRouter Error: " . $e->getMessage());
            return "Sorry, something went wrong with OpenRouter.";
        }
    }

    private function processUserResponse($response)
    {
        $response = strtolower(trim($response)); // Normalize input

        $commands = [
            "udisha" => fn() => "Hello Udisha! How can I help you today? 😊",
            "hi" => fn() => "Hello! How can I help you today? 😊",
            "hello" => fn() => "Hi there! 👋 What can I do for you?",
            "hey" => fn() => "Hey! Ready to chat anytime!",
            "how are you" => fn() => "I'm just code, but I'm running great! How about you?",
            "what's your name" => fn() => "I'm your friendly Laravel chatbot. You can call me ChatBuddy 🤖",
            "help" => fn() => "You can ask me about weather, time, general questions, or say 'joke', 'quote', 'time', etc.",
            "joke" => fn() => "Why don't developers like nature? It has too many bugs! 🐛😂",
            "quote" => fn() => "“The only way to do great work is to love what you do.” — Steve Jobs",
            "time" => fn() => "Current server time is " . now()->format('H:i A'),
            "date" => fn() => "Today's date is " . now()->format('l, F j, Y'),
            "bye" => fn() => "Goodbye! Come back if you have more questions. 👋",
            "thanks" => fn() => "You're welcome! 😊",
            "thank you" => fn() => "Anytime! Let me know if you need more help.",
            "who made you" => fn() => "I was built using Laravel and OpenRouter AI by my awesome developer!",
            "what can you do" => fn() => "I can answer questions, tell jokes, show the time, and more. Try typing 'help'.",
        ];

        // Exact match
        if (isset($commands[$response])) {
            return $commands[$response]();
        }

        // Optional: Fallback to keyword search
        foreach ($commands as $key => $callback) {
            if (str_contains($response, $key)) {
                return $callback();
            }
        }

        return "Sorry, I didn't understand that. Try saying 'help' to see what I can do!";
    }
}
