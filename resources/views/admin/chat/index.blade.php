@extends('layouts.admin')

{{-- Tambahkan CSS Kustom di bagian <head> layout Anda atau langsung di sini --}}
@push('styles')
<style>
    .chat-container {
        display: flex;
        height: calc(100vh - 150px);
        /* Sesuaikan tinggi sesuai kebutuhan layout Anda */
    }

    .chat-sidebar {
        min-width: 320px;
        max-width: 320px;
        display: flex;
        flex-direction: column;
        border-right: 1px solid #dee2e6;
    }

    .chat-sidebar-header {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
        flex-shrink: 0;
    }

    .chat-list {
        overflow-y: auto;
        flex-grow: 1;
    }

    .chat-item {
        display: flex;
        align-items: center;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #f0f0f0;
        text-decoration: none;
        color: inherit;
        transition: background-color 0.2s ease;
    }

    .chat-item:hover {
        background-color: #f8f9fa;
    }

    .chat-item.active {
        background-color: #e0eafc;
        /* Warna biru muda untuk item aktif */
    }

    .chat-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background-color: #0d6efd;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
        margin-right: 15px;
    }

    .chat-item-body {
        width: calc(100% - 60px);
        /* 45px avatar + 15px margin */
    }

    .chat-item-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-name {
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-time {
        font-size: 0.75rem;
        color: #6c757d;
        flex-shrink: 0;
        margin-left: 10px;
    }

    .chat-last-message {
        font-size: 0.85rem;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 2px;
    }

    .unread-badge {
        font-size: 0.7rem;
        font-weight: bold;
        padding: 0.2em 0.5em;
    }

    .chat-main {
        flex-grow: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background-color: #f8f9fa;
    }

    .chat-main .placeholder-text {
        text-align: center;
        color: #adb5bd;
    }

    .chat-main .placeholder-text i {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
    .chat-subtitle {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .text-truncate {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')
<div class="chat-container">
    {{-- Kolom Kiri: Daftar Chat --}}
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <h5 class="mb-0">Daftar Percakapan</h5>
        </div>

        <div class="chat-list">
            @forelse($rooms as $room)
            @php
            $user = $room->permohonan->user;
            $lastMessage = $room->lastMessage;
            $status = $room->permohonan->status;
            $isUnread = $lastMessage && $lastMessage->user_id !== auth()->id() && empty($lastMessage->read_at);
            @endphp
            <a href="{{ route('admin.chat.room', $room->permohonan_data_id) }}"
                class="chat-item {{ ($room->permohonan_data_id == ($currentRoomId ?? 0)) ? 'active' : '' }}">

                <div class="chat-avatar">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>

                <div class="chat-item-body">
                    <div class="chat-item-top">
                        <div class="chat-name">{{ $user->name ?? 'User tidak diketahui' }}</div>
                        <span class="badge rounded-pill 
                        @if($status == 'selesai') bg-success
                        @elseif($status == 'ditolak') bg-danger
                        @elseif($status == 'diajukan') bg-warning
                        @else bg-info
                        @endif
                        ms-2">
                        {{ ucfirst($status) }}
                    </span>
                        <div class="chat-time">
                            {{ $lastMessage?->created_at?->diffForHumans() }}
                        </div>
                    </div>
                    <div class="chat-subtitle">
                        No: {{ $room->permohonan->nomor_permohonan ?? 'N/A' }}
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="chat-last-message">
                            {{ $lastMessage->message ?? 'Belum ada pesan' }}
                        </div>
                        @if($isUnread)
                        <span class="badge bg-primary rounded-pill unread-badge">Baru</span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="p-3 text-center text-muted">
                Tidak ada percakapan.
            </div>
            @endforelse
        </div>
    </div>

    {{-- Kolom Kanan: Placeholder jika tidak ada chat yang dipilih --}}
    <div class="chat-main">
        <div class="placeholder-text">
            {{-- Menggunakan Bootstrap Icons, ganti jika perlu --}}
            <i class="bi bi-chat-dots-fill"></i>
            <h4>Pilih percakapan</h4>
            <p>Pilih salah satu percakapan di samping untuk memulai.</p>
        </div>
    </div>
</div>
@endsection