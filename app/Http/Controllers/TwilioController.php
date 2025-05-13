<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwilioController extends Controller
{
    public function handleIncomingMessage(Request $request)
    {
        Log::info(sprintf('Incoming Message Received' . $request['From'] . '📥 : %s', json_encode($request['Body'], JSON_PRETTY_PRINT)));
    }
}
