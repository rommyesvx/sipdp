<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\PermohonanData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Tampilkan halaman live chat untuk permohonan tertentu
     */
    public function index($id)
    {
        $messages = ChatMessage::where('permohonan_data_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        ChatMessage::where('permohonan_data_id', $id)
            ->where('user_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        return view('chat.index', [
            'messages' => $messages,
            'permohonan_data_id' => $id,
        ]);
    }

    public function store(Request $request, $id)
    {
        ChatMessage::create([
            'permohonan_data_id' => $id,
            'user_id' => auth()->id(),
            'message' => $request->input('message'),
        ]);

        // cek role
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'admin') {
            return redirect()->route('admin.chat.room', $id);
        } else {
            return redirect()->route('chat.index', $id);
        }
    }


    public function adminIndex()
    {
        $rooms = ChatMessage::select('permohonan_data_id', DB::raw('MAX(created_at) as last_message_time'))
            ->groupBy('permohonan_data_id')
            ->orderByDesc('last_message_time')
            ->with([
                'lastMessage.user',
                'permohonan.user'
            ])
            ->get();

        return view('admin.chat.index', compact('rooms'));
    }


    public function adminRoom($permohonan_data_id)
    {
        $messages = ChatMessage::where('permohonan_data_id', $permohonan_data_id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $permohonan = PermohonanData::with('user')->findOrFail($permohonan_data_id);

        ChatMessage::where('permohonan_data_id', $permohonan_data_id)
            ->whereNull('read_at')
            ->where('user_id', '!=', auth()->id())
            ->update(['read_at' => now()]);

        return view('admin.chat.room', compact('messages', 'permohonan'));
    }

    public function fetchMessages($permohonan_data_id)
    {
        $messages = ChatMessage::where('permohonan_data_id', $permohonan_data_id)
            ->with(['user' => function ($q) {
                $q->select('id', 'name'); // ambil field penting saja
            }])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function fetchRooms()
    {
        $rooms = PermohonanData::with([
            'user',
            'lastMessage.user'
        ])
            ->whereHas('chatMessages') // hanya permohonan yang sudah punya chat
            ->orderByDesc(
                ChatMessage::select('created_at')
                    ->whereColumn('permohonan_data_id', 'permohonan_data.id')
                    ->latest()
                    ->take(1)
            )
            ->get();

        return response()->json($rooms);
    }
    public function unreadCount()
    {
        $count = ChatMessage::whereNull('read_at')
            ->whereHas('user', function ($q) {
                $q->where('role', 'user'); // hanya pesan yang dikirim oleh user
            })
            ->count();

        return response()->json(['unread' => $count]);
    }
    public function preview()
    {
        $userId = Auth::id();

        // Ambil chat yang belum dibaca dari admin
        $unreadChats = ChatMessage::with('permohonan', 'user')
            ->whereNull('read_at')                  // belum dibaca
            ->where('user_id', '!=', $userId)      // hanya pesan dari admin
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($chat) {
                return [
                    'permohonan_data_id' => $chat->permohonan_data_id,
                    'message' => $chat->message,
                    'nomor_permohonan' => $chat->permohonan->nomor_permohonan ?? 'N/A',
                    'created_at' => $chat->created_at,
                ];
            })
            ->take(5);

        return response()->json($unreadChats);
    }
}
