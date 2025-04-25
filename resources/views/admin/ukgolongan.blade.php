@extends('layouts.app')

@section('title', 'Data Pegawai')

@section('content')
    <h2 class="mb-4">Data ASN Berdasarkan Jenis Kelamin</h2>

    <!-- Tabel -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
            </tr>
        </thead>
        <tbody>
        @foreach($pegawai as $p)
            <tr>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->jenisKelamin }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
