@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Log Aktivitas Sistem</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="logTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Waktu</th>
                        <th>Log Name</th>
                        <th>Properti</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $index => $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $index }}</td>
                            <td>{{ $log->causer?->name ?? 'System' }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->created_at->format('d M Y, H:i') }}</td>
                            <td>{{ $log->log_name }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#logDetailModal{{ $log->id }}">
                                    Detail
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="logDetailModal{{ $log->id }}" tabindex="-1" aria-labelledby="logDetailModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Log Aktivitas</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <pre class="mb-0 bg-light p-3 rounded"><code>{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Laravel pagination --}}
        <div class="mt-3 d-flex justify-content-center">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#logTable').DataTable({
            paging: false, // nonaktifkan paging bawaan DataTables
            info: false,   // nonaktifkan info jumlah data
            searching: true,
            ordering: true,
            language: {
                search: "Cari:",
                zeroRecords: "Tidak ada aktivitas ditemukan",
                emptyTable: "Belum ada aktivitas terekam."
            }
        });
    });
</script>
@endpush
