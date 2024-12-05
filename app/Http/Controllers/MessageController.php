<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);
    
        $message = Message::create([
            'monthly_compliance_id' => $id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        broadcast(new NewChatMessage($message)); // Broadcast the message event
    
        return response()->json(['success' => true, 'message' => $message]);
    }

    public function fetch($monthlyComplianceId)
    {
        $messages = Message::where('monthly_compliance_id', $monthlyComplianceId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}
