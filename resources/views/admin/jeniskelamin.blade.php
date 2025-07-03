@extends('layouts.admin')

@section('title', 'Statistik Pegawai Berdasarkan Jenis Kelamin')

@section('content')
<h2 class="mb-4 fw-bold">Data Pegawai Berdasarkan Jenis Kelamin</h2>
@if($periodeTerbaru)
<p class="text-muted">Periode Data: {{ \Carbon\Carbon::parse($periodeTerbaru)->translatedFormat('F Y') }}</p>
@else
<p class="text-muted">Periode Data: Tidak tersedia</p>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table id="genderTable" class="table table-striped table-bordered" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Unit Organisasi</th>
                        <th>Jumlah Laki-laki</th>
                        <th>Jumlah Perempuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil as $unor => $jml)
                    <tr>
                        <td>{{ $unor }}</td>
                        <td>{{ $jml['L'] ?? 0 }}</td>
                        <td>{{ $jml['P'] ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-secondary fw-bold">
                    <tr>
                        <td>Total Keseluruhan</td>
                        <td id="totalLakiLaki"></td>
                        <td id="totalPerempuan"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#genderTable').DataTable({
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
                "<'row'<'col-sm-12 mt-3'B>>",

            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Export Excel',
                    className: 'btn btn-success btn-sm',
                    title: 'Statistik Jenis Kelamin Pegawai',
                    footer: true
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> Export PDF',
                    className: 'btn btn-danger btn-sm',
                    title: 'Statistik Jenis Kelamin Pegawai',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    footer: true
                }
            ],

            footerCallback: function(row, data, start, end, display) {
                let api = this.api();

                let intVal = function(i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                };

                let totalLaki = api.column(1, {
                    page: 'current'
                }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                let totalPerempuan = api.column(2, {
                    page: 'current'
                }).data().reduce((a, b) => intVal(a) + intVal(b), 0);

                api.column(1).footer().innerHTML = totalLaki;
                api.column(2).footer().innerHTML = totalPerempuan;
            },

            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Data tidak ditemukan",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Selanjutnya"
                }
            }
        });
    });
</script>
@endpush