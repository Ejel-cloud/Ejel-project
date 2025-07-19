<?php



namespace App\Http\Controllers;

use App\Models\MarketplaceChat;
use App\Models\MarketplaceMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;



class MarketplaceMessageController extends Controller
{
    /**
     * Display all messages in a chat.
     */
    public function index(MarketplaceChat $chat)
    {
        Gate::authorize('view', $chat);

        $messages = $chat->messages()
            ->with('user')
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }

    /**
     * Store a new message in a chat.
     */
    public function store(Request $request, MarketplaceChat $chat)
    {
        Gate::authorize('view', $chat);

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $chat->messages()->create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        return response()->json($message, 201);
    }
}
