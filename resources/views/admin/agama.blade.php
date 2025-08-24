@extends('layouts.admin')

@section('title', 'Statistik Pegawai Berdasarkan Agama')

@section('content')
<h2 class="mb-4">Data Unit Organisasi Berdasarkan Agama</h2>
@if($periodeTerbaru)
<p class="text-muted">Periode Data: {{ \Carbon\Carbon::parse($periodeTerbaru)->translatedFormat('F Y') }}</p>
@else
<p class="text-muted">Periode Data: Tidak tersedia</p>
@endif

@php
$allAgama = $hasil->flatMap(fn($item) => $item->keys())->unique()->sort()->values();
@endphp

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table id="agamaTable" class="table table-striped table-bordered" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Unit Organisasi</th>
                        @foreach($allAgama as $agama)
                        <th>{{ $agama }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil as $unor => $agamas)
                    <tr>
                        <td>{{ $unor }}</td>
                        @foreach($allAgama as $agama)
                        <td>{{ $agamas->get($agama, 0) }}</td>
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
        $('#agamaTable').DataTable({
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
                "<'row'<'col-sm-12 mt-3'B>>",

            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Export Excel',
                    className: 'btn btn-success btn-sm',
                    title: 'Statistik Agama Pegawai'
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> Export PDF',
                    className: 'btn btn-danger btn-sm',
                    title: 'Statistik Agama Pegawai',
                    orientation: 'landscape',
                    pageSize: 'LEGAL' // Gunakan kertas lebih lebar jika kolom banyak
                }
            ],
            scrollX: true, // Mengaktifkan scroll horizontal
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Data tidak ditemukan",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>
@endpush