<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return $this->sendWhatsAppMessage($request['From'], "Hi Udisha! How can I help you today.");
        return response('OK', 200);
    }

     private function sendWhatsAppMessage($to, $body)
    {
        $this->twilio->messages->create($to, [
            "from" => $this->from,
            "body" => $body,
            "messagingServiceSid" => "MG03f3877b92332365905959f889b1a532",
        ]);

        Log::info("WhatsApp message sent to $to: $body");
        return true;
    }
}
