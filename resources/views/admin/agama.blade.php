@extends('layouts.admin')

@section('title', 'Statistik Agama Pegawai')

@section('content')
    <h2 class="mb-4">Statistik Unit Organisasi Berdasarkan Agama dan Unit Organisasi</h2>
    @if($periodeTerbaru)
        <p class="text-muted">Periode Data Terbaru: {{ \Carbon\Carbon::parse($periodeTerbaru)->translatedFormat('d F Y') }}</p>
    @else
        <p class="text-muted">Periode Data: Tidak tersedia</p>
    @endif
    <table id="example" class="display nowrap table table-striped" style="width:100%">
        <thead class="table-dark">
            <tr>
                <th>Nama Unor</th>
                @php
                    $allAgama = $hasil->flatMap(fn($item) => $item->keys())->unique()->sort()->values();
                @endphp
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
    <script>
    $('#example').DataTable({
        dom: 'Bfrtip',
        buttons: [{
                extend: 'excelHtml5',
                title: 'Statistik Agama Pegawai'
            },
            {
                extend: 'pdfHtml5',
                title: 'Statistik Agama Pegawai',
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
