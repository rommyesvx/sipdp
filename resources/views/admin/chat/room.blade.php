@extends('layouts.admin')

{{-- CSS Kustom untuk halaman chat --}}
@push('styles')
<style>
    .chat-panel {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 150px);
        /* Sesuaikan tinggi sesuai layout admin Anda */
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        overflow: hidden;
        background-color: #fff;
    }

    .chat-header {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #dee2e6;
        background-color: #f8f9fa;
        flex-shrink: 0;
    }

    .chat-header .user-name {
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
        /* Warna latar belakang area chat */
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
        max-width: 70%;
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

    .message-info {
        display: block;
        font-size: 0.75rem;
        margin-top: 5px;
        color: #6c757d;
    }

    .message-row.sender .message-info {
        color: rgba(255, 255, 255, 0.7);
        text-align: right;
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
<div class="chat-panel">
    {{-- 1. Header Chat --}}
    <div class="chat-header">
        <a href="{{ route('admin.chat.index') }}" class="btn btn-light btn-sm me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <div class="user-name">{{ $permohonan->user->name }}</div>
            <div class="subtitle">No. Permohonan: {{ $permohonan->nomor_permohonan ?? $permohonan->id }}</div>
        </div>
    </div>

    {{-- 2. Body Chat (Area Pesan) --}}
    <div class="chat-body" id="chat-box">
        @foreach($messages as $msg)
        @php
        $isSender = $msg->user_id == auth()->id();
        @endphp
        <div class="message-row {{ $isSender ? 'sender' : 'receiver' }}">
            <div class="message-bubble">
                @if(!$isSender)
                <small class="d-block fw-bold text-primary">{{ $msg->user->name }}</small>
                @endif
                <p class="message-content">{{ $msg->message }}</p>
                <span class="message-info">{{ $msg->created_at->format('H:i') }}</span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- 3. Footer Chat (Form Input) --}}
    <div class="chat-footer">
        @php
            // Definisikan variabel di luar if/else agar lebih bersih
            $isFinalStatus = in_array($permohonan->status, ['selesai', 'ditolak']);
        @endphp

        @if($isFinalStatus)
            <div class="text-center text-muted p-3">
                Percakapan ini telah ditutup karena permohonan sudah {{ $permohonan->status }}.
            </div>
        @else
            <form action="{{ route('chat.store', $permohonan->id) }}" method="POST" id="chat-form">
                @csrf
                <div class="d-flex align-items-center">
                    <input type="text" name="message" class="form-control" placeholder="Tulis pesan..." autocomplete="off" required>
                    <button type="submit" class="btn btn-primary ms-2 flex-shrink-0">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </form>
        @endif  
    </div>

</div> 
@endsection

@push('scripts')
{{-- Pastikan jQuery sudah dimuat sebelum script ini --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Ambil ID admin yang sedang login untuk perbandingan di JavaScript
    const loggedInAdminId = {{ auth()->id() }};

    document.addEventListener('DOMContentLoaded', function() {
        const chatBox = document.getElementById('chat-box');
        // Langsung scroll ke paling bawah saat halaman dimuat
        chatBox.scrollTop = chatBox.scrollHeight;

        // Fokuskan ke input field
        const messageInput = document.querySelector('input[name="message"]');
        if (messageInput) {
            messageInput.focus();
        }
        
        // Memanggil fetchMessages pertama kali
        fetchMessages();
    });

    function fetchMessages() {
        $.get("{{ route('admin.chat.fetch', $permohonan->id) }}", function(data) {
            let chatBox = $('#chat-box');
            chatBox.empty(); // Kosongkan chat box sebelum mengisi dengan data baru

            data.forEach(msg => {
                const isSender = msg.user_id == loggedInAdminId;
                const rowClass = isSender ? 'sender' : 'receiver';
                
                const messageTime = new Date(msg.created_at).toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const senderNameHtml = !isSender 
                    ? `<small class="d-block fw-bold text-primary">${msg.user.name}</small>` 
                    : '';

                const messageHtml = `
                    <div class="message-row ${rowClass}">
                        <div class="message-bubble">
                            ${senderNameHtml}
                            <p class="message-content">${msg.message}</p>
                            <span class="message-info">${messageTime}</span>
                        </div>
                    </div>
                `;

                chatBox.append(messageHtml);
            });

            chatBox.scrollTop(chatBox[0].scrollHeight);
        });
    }

    setInterval(fetchMessages, 3000);

    $('#chat-form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(response) {
                fetchMessages(); 
                $('input[name="message"]').val(''); 
            },
            error: function(err) {
                console.error("Gagal mengirim pesan:", err);
            }
        });
    });
</script>
@endpush