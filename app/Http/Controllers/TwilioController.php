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

        $response = $this->askChatGPT($body);
        $this->sendWhatsAppMessage($from, $response);

        return response('OK', 200);
    }

    private function sendWhatsAppMessage($to, $body)
    {
        try {
            $this->twilio->messages->create($to, [
                "from" => $this->from,
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
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $message],
                ],
            ]);

            if ($response->failed()) {
                Log::error("❌ ChatGPT API failed: " . $response->body());
                return "Sorry, I couldn't process your request.";
            }

            $data = $response->json();
            $chatReply = $data['choices'][0]['message']['content'] ?? 'No response from ChatGPT.';

            Log::info("🤖 ChatGPT Response: {$chatReply}");
            return $chatReply;

        } catch (\Exception $e) {
            Log::error("❌ ChatGPT Error: " . $e->getMessage());
            return "Sorry, something went wrong with ChatGPT.";
        }
    }
}
