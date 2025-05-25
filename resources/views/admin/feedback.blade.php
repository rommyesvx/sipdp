@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Data Feedback Pengguna</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="feedbackTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Pengguna</th>
                        <th>Permohonan</th>
                        <th>Rating</th>
                        <th>Pesan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $index => $feedback)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $feedback->user->name ?? '-' }}</td>
                            <td>
                                @if ($feedback->permohonan)
                                    <a href="{{ route('admin.permohonan.show', $feedback->permohonan->id) }}" title="Lihat Detail Permohonan {{ $feedback->permohonan->id }}">
                                        {{ $feedback->permohonan->id }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $feedback->rating >= 4 ? 'success' : ($feedback->rating == 3 ? 'warning' : 'danger') }}">
                                    {{ $feedback->rating }} / 5
                                </span>
                            </td>
                            <td>{{ Str::limit($feedback->pesan ?? '-', 50) }}</td> {{-- Batasi panjang pesan agar tabel rapi --}}
                            <td>{{ \Carbon\Carbon::parse($feedback->created_at)->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada feedback yang masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#feedbackTable').DataTable({
                "language": { 
                    "sEmptyTable":   "Tidak ada data yang tersedia pada tabel ini",
                    "sProcessing":   "Sedang memproses...",
                    "sLengthMenu":   "Tampilkan _MENU_ entri",
                    "sZeroRecords":  "Tidak ditemukan data yang sesuai",
                    "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
                    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "sInfoPostFix":  "",
                    "sSearch":       "Cari:",
                    "sUrl":          "",
                    "oPaginate": {
                        "sFirst":    "Pertama",
                        "sPrevious": "Sebelumnya",
                        "sNext":     "Selanjutnya",
                        "sLast":     "Terakhir"
                    },
                    "oAria": {
                        "sSortAscending":  ": aktifkan untuk mengurutkan kolom secara menaik",
                        "sSortDescending": ": aktifkan untuk mengurutkan kolom secara menurun"
                    }
                },
                "columnDefs": [
                    { "orderable": false, "targets": [2, 4] } 
                ],
                dom: 'Bfrtip', 
                buttons: [
                    'excel', 'pdf'
                ]
            });
        });
    </script>
@endpush
