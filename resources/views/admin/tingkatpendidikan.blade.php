@extends('layouts.admin')

@section('title', 'Data Pegawai Berdasarkan Pendidikan')

@section('content')
<h2 class="mb-4">Data Pegawai Berdasarkan Pendidikan Terakhir</h2>
@if($periodeTerbaru)
        <p class="text-muted">Periode Data Terbaru: {{ \Carbon\Carbon::parse($periodeTerbaru)->translatedFormat('d F Y') }}</p>
    @else
        <p class="text-muted">Periode Data: Tidak tersedia</p>
    @endif
@php
$allTingkat = $hasil->flatMap(fn($item) => $item->keys())->unique()->sort()->values();
@endphp
<div class="table-responsive">
    <table id="example" class="display nowrap table table-striped" style="width:100%">
        <thead class="table-dark">
            <tr>
                <th>Nama Unor</th>
                @foreach($allTingkat as $tingkat)
                <th>{{ $tingkat }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($hasil as $unor => $tingkats)
            <tr>
                <td>{{ $unor }}</td>
                @foreach($allTingkat as $tingkat)
                <td>{{ $tingkats->get($tingkat, 0) }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $('#example').DataTable({
        dom: 'Bfrtip',
        buttons: [{
                extend: 'excelHtml5',
                title: 'Statistik Tingkat Pendidikan Pegawai'
            },
            {
                extend: 'pdfHtml5',
                title: 'Statistik Tingkat Pendidikan Pegawai',
                orientation: 'landscape',
                pageSize: 'A4'
            }
        ],
        responsive: true,
        scrollX: true,
        paging: true,
        ordering: true
    });
</script>
@endsection