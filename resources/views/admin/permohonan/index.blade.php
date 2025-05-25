@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Daftar Permohonan Data</h2>
        <span class="text-muted">Total: {{ $permohonans->count() }} permohonan</span>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Nama Pemohon</th>
                    <th scope="col">Jenis Data</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permohonans as $permohonan)
                <tr>
                    <td>{{ $permohonan->user->name }}</td>
                    <td>{{ $permohonan->jenis_data }}</td>
                    <td>
                        <span class="badge 
                            @if($permohonan->status === 'selesai') bg-success 
                            @elseif($permohonan->status === 'diproses') bg-warning text-dark
                            @elseif($permohonan->status === 'ditolak') bg-danger text-dark
                            @else bg-secondary @endif">
                            {{ ucfirst($permohonan->status ?? 'Belum Diproses') }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.permohonan.show', $permohonan->id) }}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada permohonan data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
