@extends('layouts.admin')


@section('content')
    <h2 class="mb-4">Data Pegawai</h2>
    
    <!-- <table id="example" class="display nowrap table table-striped" style="width:100%">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>NIP</th>
                <th>NIP Lama</th>
                <th>Nama</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawai as $p)
                <tr>
                    <td>{{ $p->idPegawai }}</td>
                    <td>{{ $p->nip }}</td>
                    <td>{{ $p->nipLama }}</td>
                    <td>{{ $p->nama }}</td>
                </tr>
            @endforeach
        </tbody>
    </table> -->
@endsection