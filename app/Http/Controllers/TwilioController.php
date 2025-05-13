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
        $sid            = config("twilio.api_keys.sid");
        $token          = config("twilio.api_keys.token");
        $this->twilio   = new Client($sid, $token);
        $this->from     = "whatsapp:" . config("twilio.api_keys.from");
    }


    public function handleIncomingMessage(Request $request)
    {
        Log::info(sprintf('Incoming Message Received' . $request['From'] . '📥 : %s', json_encode($request['Body'], JSON_PRETTY_PRINT)));
        $response =  $this->askChatGPT($request['Body']);
        return $this->sendWhatsAppMessage($request['From'], $response);
        return response('OK', 200);
    }

    private function sendWhatsAppMessage($to, $body)
    {
        Log::info('From: ' . $this->from . ' To: ' . $to . ' Body: ' . $body);
        try {
            $this->twilio->messages->create($to, [
                "from" => "whatsapp:+14155238886",
                "body" => $body,
            ]);
        } catch (\Exception $e) {
            Log::info("Twilio Sent Message Error", $e->getMessage());
        }


        Log::info("WhatsApp message sent to $to: $body");
        return true;
    }

    private function askChatGPT($message)
    {

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.key'),
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $message],
                ],
            ]);
        } catch (\Exception $e) {
            Log::info("Chatgpt Error", $e->getMessage());
        }

        $data = $response->json();
       Log::info(sprintf('ChatGPT Response : %s', json_encode($response, JSON_PRETTY_PRINT)));
        return $data['choices'][0]['message']['content'] ?? 'No response from ChatGPT.';
    }
}
