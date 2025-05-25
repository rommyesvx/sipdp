@extends('layouts.admin') {{-- Ganti sesuai layout yang kamu gunakan untuk Kepala Bidang --}}

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-primary fw-bold">Daftar Permohonan Eskalasi</h2>

    @if($permohonanList->isEmpty())
        <div class="alert alert-info">
            Tidak ada permohonan yang menunggu persetujuan saat ini.
        </div>
    @else
        <div class="table-responsive shadow-sm">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Pemohon</th>
                        <th>Jenis Data</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permohonanList as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->jenis_data }}</td>
                        <td>{{ Str::limit($item->tujuan, 50) }}</td>
                        <td><span class="badge bg-warning text-dark">{{ ucfirst($item->status) }}</span></td>
                        <td>
                            <a href="{{ route('kepala.permohonan.show', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                Detail & Tindakan
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
