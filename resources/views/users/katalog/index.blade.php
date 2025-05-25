@extends('layouts.appUser')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-5 fw-bold">Katalog Data Pegawai</h2>

    <div class="row g-4">
        @foreach ($katalog as $data)
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary fw-semibold">{{ $data['nama'] }}</h5>
                        <p class="card-text text-muted">{{ $data['deskripsi'] }}</p>
                        <ul class="list-unstyled small mb-3">
                            <li><strong>Format:</strong> {{ $data['format'] }}</li>
                            <li><strong>Update:</strong> {{ $data['frekuensi'] }}</li>
                            <li><strong>Akses:</strong> {{ $data['akses'] }}</li>
                        </ul>
                        <button class="btn btn-outline-primary btn-sm" disabled>
                            Lihat Detail (Static)
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
