@extends('layouts.appUser')

@include('layouts.repeatHeader')
@section('content')

<section class="py-5 bg-light min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card shadow rounded-4 border-0">
          <div class="card-body p-5">
            <h2 class="fw-bold mb-4 text-center">Formulir Permohonan Data</h2>
            <p class="text-muted text-center mb-4">Silakan isi data permohonan dengan lengkap dan benar.</p>

            <form action="{{ route('permohonan.store') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="row g-4">

                <!-- Tujuan Permohonan Data -->
                <div class="col-12">
                  <label class="form-label fw-semibold">Tujuan Permohonan Data</label>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="tujuan" id="penelitian" value="Penelitian" required>
                    <label class="form-check-label" for="penelitian">Penelitian</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="tujuan" id="evaluasi" value="Evaluasi">
                    <label class="form-check-label" for="evaluasi">Data Pensiun</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="tujuan" id="lainnya" value="Lainnya">
                    <label class="form-check-label" for="lainnya">Lainnya</label>
                  </div>
                </div>
                <!-- Tipe File -->
                <div class="col-12">
                  <label class="form-label fw-semibold">Tipe File yang diperlukan</label>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipe" id="pdf" value="pdf" required>
                    <label class="form-check-label" for="penelitian">PDF</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipe" id="excel" value="excel">
                    <label class="form-check-label" for="evaluasi">Excel</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipe" id="csv" value="csv">
                    <label class="form-check-label" for="lainnya">CSV</label>
                  </div>
                </div>

                <!-- Jenis Data yang Dibutuhkan -->
                <div class="col-12">
                  <label for="jenis_data" class="form-label fw-semibold">Sebutkan Data yang Dibutuhkan</label>
                  <textarea class="form-control rounded-3" id="jenis_data" name="jenis_data" rows="3" required placeholder="Contoh: Data pegawai berdasarkan golongan, Data kehadiran 2022, dll."></textarea>
                </div>

                <!-- File Surat Pengantar -->
                <div class="col-12">
                  <label for="lampiran" class="form-label fw-semibold">Surat Pengantar</label>
                  <input type="file" class="form-control rounded-3" id="file_permohonan" name="file_permohonan">
                </div>

                <!-- Catatan Tambahan -->
                <div class="col-12">
                  <label for="catatan" class="form-label fw-semibold">Catatan Tambahan</label>
                  <textarea class="form-control rounded-3" id="catatan" name="catatan" rows="3" placeholder="Tambahkan keterangan tambahan jika diperlukan."></textarea>
                </div>

                <!-- Submit -->
                <div class="col-12 mt-4">
                  <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">Kirim Permohonan</button>
                </div>

              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@include('layouts.repeatFooter')
@endsection
