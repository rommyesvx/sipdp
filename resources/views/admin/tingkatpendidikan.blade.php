@extends('layouts.admin')

@section('title', 'Data Pegawai Berdasarkan Pendidikan')

@section('content')
<h2 class="mb-4">Data Unit Organisasi Berdasarkan Pendidikan Terakhir</h2>
@if($periodeTerbaru)
    <p class="text-muted">Periode Data: {{ \Carbon\Carbon::parse($periodeTerbaru)->translatedFormat('F Y') }}</p>
@else
    <p class="text-muted">Periode Data: Tidak tersedia</p>
@endif

@php
    $allTingkat = $hasil->flatMap(fn($item) => $item->keys())->unique()->sort()->values();
@endphp

<div class="card shadow-sm border-0">
    <div class="card-body">
        {{-- class="table-responsive" akan menangani scroll jika diperlukan --}}
        <div class="table-responsive">
            {{-- Tabel diberi kelas Bootstrap standar agar dikenali oleh integrasi DataTables --}}
            <table id="pendidikanTable" class="table table-striped table-bordered" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Unit Organisasi</th>
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
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#pendidikanTable').DataTable({
        // Tata letak standar Bootstrap: L=Length, B=Buttons, f=filter, r=processing, t=table, i=info, p=pagination
        dom:  "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
              "<'row'<'col-sm-12 mt-3'B>>", // Menempatkan tombol di bawah
        
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Export Excel',
                className: 'btn btn-success btn-sm', 
                title: 'Statistik Tingkat Pendidikan Pegawai'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> Export PDF',
                className: 'btn btn-danger btn-sm',
                title: 'Statistik Tingkat Pendidikan Pegawai',
                orientation: 'landscape',
                pageSize: 'A4'
            }
        ],
        scrollX: true, 
        language: {
          
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: { previous: "Sebelumnya", next: "Selanjutnya"}
        }
    });
});
</script>
@endpush