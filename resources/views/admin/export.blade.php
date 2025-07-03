@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Persiapan Ekspor Data Pegawai</h2>

<!-- Form Filter -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="unitKerja" class="form-label">Unit Kerja</label>
                    <select class="form-select" id="unitKerja">
                        <option selected disabled>Pilih Unit Kerja</option>
                        <option value="BKD">BKD</option>
                        <option value="Dinas Pendidikan">Dinas Pendidikan</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenisKelamin">
                        <option selected disabled>Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="pendidikan" class="form-label">Pendidikan Terakhir</label>
                    <select class="form-select" id="pendidikan">
                        <option selected disabled>Pilih Pendidikan</option>
                        <option>SMA</option>
                        <option>D3</option>
                        <option>S1</option>
                        <option>S2</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="usia" class="form-label">Rentang Usia</label>
                    <input type="text" class="form-control" id="usia" placeholder="Contoh: 25-40">
                </div>

                <div class="col-md-4">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <input type="text" class="form-control" id="jabatan" placeholder="Contoh: Analis Kepegawaian">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tampilkan Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Hasil Filter -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="mb-3">Hasil Data Pegawai</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Unit Kerja</th>
                        <th>Jabatan</th>
                        <th>Jenis Kelamin</th>
                        <th>Pendidikan</th>
                        <th>Usia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Rizky Ananda</td>
                        <td>19781212 200012 1 001</td>
                        <td>Dinas Pendidikan</td>
                        <td>Guru Madya</td>
                        <td>L</td>
                        <td>S1</td>
                        <td>42</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Dewi Kartika</td>
                        <td>19850615 201002 2 002</td>
                        <td>BKD</td>
                        <td>Analis Kepegawaian</td>
                        <td>P</td>
                        <td>S2</td>
                        <td>39</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end gap-2">
            <button class="btn btn-outline-success"><i class="bi bi-file-earmark-excel"></i> Ekspor Excel</button>
            <button class="btn btn-outline-danger"><i class="bi bi-file-earmark-pdf"></i> Ekspor PDF</button>
        </div>
    </div>
</div>
@endsection
