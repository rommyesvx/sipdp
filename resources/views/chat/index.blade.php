@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.newAppUser')

{{-- CSS Kustom untuk halaman chat --}}
@push('styles')
<style>
    .chat-panel {
        display: flex;
        flex-direction: column;
        height: 75vh;
        max-height: 700px;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        overflow: hidden;
        background-color: #fff;
        margin-top: 1rem;
    }

    .chat-header {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #dee2e6;
        background-color: #f8f9fa;
        flex-shrink: 0;
    }
    
    .chat-header .title {
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .chat-header .subtitle {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .chat-body {
        flex-grow: 1;
        padding: 1.5rem;
        overflow-y: auto;
        background-color: #e9ecef;
    }

    .message-row {
        display: flex;
        margin-bottom: 1rem;
    }

    .message-row.sender {
        justify-content: flex-end;
    }
    .message-row.receiver {
        justify-content: flex-start;
    }

    .message-bubble {
        max-width: 75%;
        padding: 0.65rem 1rem;
        border-radius: 1rem;
        position: relative;
    }
    
    .message-row.sender .message-bubble {
        background-color: #0d6efd;
        color: white;
        border-bottom-right-radius: 0.25rem;
    }

    .message-row.receiver .message-bubble {
        background-color: #ffffff;
        color: #212529;
        border: 1px solid #dee2e6;
        border-bottom-left-radius: 0.25rem;
    }
    
    .message-content {
        margin: 0;
        word-wrap: break-word;
    }
    
    .message-sender-name {
        display: block;
        font-size: 0.8rem;
        font-weight: bold;
        color: #0d6efd;
        margin-bottom: 4px;
    }

    .message-info {
        display: block;
        font-size: 0.75rem;
        margin-top: 5px;
        color: #6c757d;
        text-align: right;
    }
    
    .message-row.sender .message-info {
        color: rgba(255, 255, 255, 0.75);
    }

    .chat-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid #dee2e6;
        background-color: #f8f9fa;
        flex-shrink: 0;
    }
    
    .chat-footer .form-control {
        border-radius: 2rem;
        padding: 0.5rem 1.2rem;
    }
    
    .chat-footer .btn {
        border-radius: 50%;
        width: 45px;
        height: 45px;
    }
</style>
{{-- Memuat library ikon seperti Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')
<div class="container">
    <div class="chat-panel">
        {{-- 1. Header Chat --}}
        <div class="chat-header">
            <a href="{{ route('permohonan.show', $permohonan->id ?? $permohonan_data_id) }}" 
               class="btn btn-light btn-sm me-3" 
               title="Kembali ke Detail Permohonan">
                <i class="bi bi-arrow-left"></i>
            </a>

            <div>
                <div class="title">Live Chat</div>
                <div class="subtitle">Permohonan #{{ $permohonan->nomor_permohonan ?? $permohonan_data_id }}</div>
            </div>
        </div>

        {{-- 2. Body Chat (Area Pesan) --}}
        <div class="chat-body" id="chat-box">
            @forelse($messages as $msg)
                @php
                    $isSender = $msg->user_id == auth()->id();
                @endphp
                <div class="message-row {{ $isSender ? 'sender' : 'receiver' }}">
                    <div class="message-bubble">
                        @if(!$isSender)
                            <span class="message-sender-name">{{ $msg->user->name ?? 'Admin' }}</span>
                        @endif
                        
                        <p class="message-content">{{ $msg->message }}</p>
                        <span class="message-info">{{ $msg->created_at->format('H:i') }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted">
                    <p>Belum ada pesan. Mulai percakapan Anda!</p>
                </div>
            @endforelse
        </div>

        {{-- 3. Footer Chat (Form Input) --}}
        <div class="chat-footer">
            <form action="{{ route('chat.store', $permohonan_data_id) }}" method="POST" id="chat-form">
                @csrf
                <div class="d-flex align-items-center">
                    <input type="text" name="message" class="form-control" placeholder="Tulis pesan..." autocomplete="off" required>
                    <button type="submit" class="btn btn-primary ms-2 flex-shrink-0">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- JavaScript untuk auto-scroll ke pesan terakhir --}}
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const permohonanId = {{ $permohonan->id ?? $permohonan_data_id }};
    const chatBox = $('#chat-box');
    const chatForm = $('#chat-form');
    const messageInput = chatForm.find('input[name="message"]');

    // Render pesan ke chat box
    function renderMessages(messages) {
        chatBox.html('');
        messages.forEach(msg => {
            const isSender = msg.user_id === {{ auth()->id() }};
            const rowClass = isSender ? 'sender' : 'receiver';
            const senderName = !isSender ? (msg.user ? msg.user.name : 'Admin') : '';
            const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

            chatBox.append(`
                <div class="message-row ${rowClass}">
                    <div class="message-bubble">
                        ${senderName ? `<span class="message-sender-name">${senderName}</span>` : ''}
                        <p class="message-content">${msg.message}</p>
                        <span class="message-info">${time}</span>
                    </div>
                </div>
            `);
        });
        chatBox.scrollTop(chatBox[0].scrollHeight);
    }

    // Fetch pesan dari server
    function loadMessages() {
        $.get("{{ route('chat.fetch', ':id') }}".replace(':id', permohonanId), function(data) {
            renderMessages(data);
        });
    }

    // Jalankan load pertama kali
    loadMessages();

    // Polling tiap 3 detik
    setInterval(loadMessages, 3000);

    // Kirim pesan via AJAX
    chatForm.submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('chat.send', ':id') }}".replace(':id', permohonanId),
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                message: messageInput.val()
            },
            success: function() {
                messageInput.val('');
                loadMessages(); // refresh langsung
            }
        });
    });
</script>
@endpush
