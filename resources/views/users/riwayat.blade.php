@extends('layouts.appUser')
@include('layouts.repeatHeader')
@section('content')

@section('content')
    <div class="container py-5">
        <h3 class="mb-4">Riwayat Permohonan Data</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($permohonans->isEmpty())
            <div class="alert alert-info">Belum ada permohonan data yang diajukan.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Data yang diajukan</th>
                            <th>Tanggal Diajukan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permohonans as $index => $data)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $data->jenis_data }}</td>
                                <td>{{ $data->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($data->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($data->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                <a href="{{ route('permohonan.show', $data->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection