@extends('layouts.admin')

@section('title', 'Data Pegawai')

@section('content')
<h2 class="mb-4">Data OPD Berdasarkan Jenis Kelamin</h2>

@if($periodeTerbaru)
        <p class="text-muted">Periode Data Terbaru: {{ \Carbon\Carbon::parse($periodeTerbaru)->translatedFormat('d F Y') }}</p>
    @else
        <p class="text-muted">Periode Data: Tidak tersedia</p>
    @endif

<table id="example" class="display nowrap table table-striped" style="width:100%">
    <thead class="table-dark">
        <tr>
            <th>Nama Unor</th>
            <th>Jumlah Laki-laki</th>
            <th>Jumlah Perempuan</th>
        </tr>
    </thead>
    <tbody>  
    @foreach($hasil as $unor => $jml)
            <tr>
                <td>{{ $unor }}</td>
                <td>{{ $jml['L'] }}</td>
                <td>{{ $jml['P'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot class="table-secondary fw-bold">
        <tr>
            <td>Total</td>
            <td id="totalLakiLaki"></td>
            <td id="totalPerempuan"></td>
        </tr>
    </tfoot>
</table>
<script>
        $(document).ready(function() {
            $('#example').DataTable({
                // Opsi export
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'pdf', 'print'],

                // Callback untuk footer
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Fungsi untuk konversi ke integer
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\.,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // Total semua laki-laki
                    var totalLaki = api
                        .column(1, {
                            page: 'all'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total semua perempuan
                    var totalPerempuan = api
                        .column(2, {
                            page: 'all'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update ke footer
                    $('#totalLakiLaki').html(totalLaki);
                    $('#totalPerempuan').html(totalPerempuan);
                }
            });
        });
    </script>
@endsection