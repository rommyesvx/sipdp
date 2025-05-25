@extends('layouts.admin') {{-- Menggunakan layout yang Anda berikan --}}

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li> {{-- Sesuaikan jika ada dashboard khusus Kabid --}}
        <li class="breadcrumb-item active" aria-current="page">Alat Pelaporan</li>
    </ol>
</nav>

<h2 class="mb-4 display-6">Alat Pelaporan Kepala Bidang</h2>

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <i class="bi bi-filter-circle-fill"></i> Kriteria Laporan
    </div>
    <div class="card-body">
        <form id="formLaporan" action="{{-- {{ route('kepala_bidang.laporan.generate') }} --}}" method="POST" target="_blank">
            @csrf {{-- Tetap disertakan untuk praktik yang baik, meskipun statis --}}
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="jenis_laporan" class="form-label fw-bold">Jenis Laporan</label>
                    <select class="form-select" id="jenis_laporan" name="jenis_laporan">
                        <option value="kinerja_periodik" selected>Laporan Kinerja Periodik Permohonan</option>
                        <option value="feedback_rangkuman">Laporan Rangkuman Feedback</option>
                        <option value="eskalasi_detail">Laporan Detail Permohonan Dieskalasi</option>
                        <option value="permohonan_per_unit">Laporan Permohonan per Unit Kerja</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tanggal_mulai" class="form-label fw-bold">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="2024-01-01">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_selesai" class="form-label fw-bold">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="2024-03-31">
                </div>

                <div class="col-md-4">
                    <label for="status_permohonan" class="form-label">Status Permohonan (Filter Opsional)</label>
                    <select class="form-select" id="status_permohonan" name="status_permohonan">
                        <option value="">Semua Status</option>
                        <option value="diajukan">Diajukan</option>
                        <option value="diproses">Diproses</option>
                        <option value="dieskalasi">Dieskalasi</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="unit_kerja" class="form-label">Unit Kerja (Filter Opsional)</label>
                    <select class="form-select" id="unit_kerja" name="unit_kerja">
                        <option value="">Semua Unit Kerja</option>
                        <option value="bkpsdm">BKPSDM</option>
                        <option value="dinkes">Dinas Kesehatan</option>
                        <option value="dispendik">Dinas Pendidikan</option>
                        <option value="inspektorat">Inspektorat</option>
                    </select>
                </div>

                <div class="col-12 mt-4">
                    <button type="button" class="btn btn-primary me-2" onclick="alert('Fungsi Tampilkan Pratinjau belum diimplementasikan (hanya tampilan statis).')">
                        <i class="bi bi-eye-fill"></i> Tampilkan Pratinjau
                    </button>
                    <button type="button" class="btn btn-danger me-2" onclick="alert('Fungsi Unduh PDF belum diimplementasikan.')">
                        <i class="bi bi-file-earmark-pdf-fill"></i> Unduh PDF
                    </button>
                    <button type="button" class="btn btn-success" onclick="alert('Fungsi Unduh Excel belum diimplementasikan.')">
                        <i class="bi bi-file-earmark-excel-fill"></i> Unduh Excel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="previewArea" class="mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
           <i class="bi bi-table"></i> Pratinjau: Laporan Kinerja Periodik Permohonan
        </div>
        <div class="card-body">
            <div class="mb-3">
                <p class="mb-1"><strong>Periode Laporan:</strong> 01 Januari 2024 - 31 Maret 2024</p>
                <p class="mb-1"><strong>Status Permohonan:</strong> Semua Status</p>
                <p class="mb-1"><strong>Unit Kerja:</strong> Semua Unit Kerja</p>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Bulan/Tahun</th>
                            <th scope="col" class="text-center">Permohonan Masuk</th>
                            <th scope="col" class="text-center">Selesai</th>
                            <th scope="col" class="text-center">Ditolak</th>
                            <th scope="col" class="text-center">Dieskalasi</th>
                            <th scope="col" class="text-center">Rata-rata Waktu Proses (Hari)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Januari 2024</td>
                            <td class="text-center">120</td>
                            <td class="text-center">100</td>
                            <td class="text-center">10</td>
                            <td class="text-center">5</td>
                            <td class="text-center">2.5</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Februari 2024</td>
                            <td class="text-center">150</td>
                            <td class="text-center">125</td>
                            <td class="text-center">15</td>
                            <td class="text-center">8</td>
                            <td class="text-center">3.1</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Maret 2024</td>
                            <td class="text-center">135</td>
                            <td class="text-center">110</td>
                            <td class="text-center">12</td>
                            <td class="text-center">6</td>
                            <td class="text-center">2.8</td>
                        </tr>
                        <tr class="table-primary fw-bold">
                            <td colspan="2" class="text-end">Total Kuartal 1:</td>
                            <td class="text-center">405</td>
                            <td class="text-center">335</td>
                            <td class="text-center">37</td>
                            <td class="text-center">19</td>
                            <td class="text-center">~2.8</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 text-muted">
                <small>Laporan ini (seolah-olah) dihasilkan pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i:s') }} WIB.</small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisLaporanSelect = document.getElementById('jenis_laporan');
        const previewTitleElement = document.querySelector('#previewArea .card-header'); // Mengambil elemen judul pratinjau

        if (jenisLaporanSelect && previewTitleElement) {
            jenisLaporanSelect.addEventListener('change', function() {
                console.log('Jenis laporan dipilih:', this.value);
                let newTitleText = "Pratinjau: ";
                let selectedOptionText = this.options[this.selectedIndex].text; // Mengambil teks dari opsi yang dipilih
                newTitleText += selectedOptionText;

                // Mengubah judul pratinjau secara dinamis (hanya teks judul)
                previewTitleElement.innerHTML = `<i class="bi bi-table"></i> ${newTitleText}`;
                
                // Catatan: Mengubah isi tabel dummy berdasarkan pilihan ini memerlukan JavaScript yang lebih kompleks
                // dan penyiapan data dummy untuk setiap jenis laporan, yang diluar lingkup tampilan statis ini.
                // Untuk sekarang, hanya judul pratinjau yang akan berubah.
                alert('Pratinjau tabel di bawah ini masih merupakan data dummy untuk "Laporan Kinerja Periodik Permohonan" dan tidak berubah sesuai pilihan jenis laporan di mode statis ini. Hanya judul pratinjau yang diupdate.');
            });
        }
    });
</script>
@endpush