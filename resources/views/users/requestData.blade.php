@extends('layouts.newAppUser')

@section('title', 'Formulir Permohonan Data')

@push('styles')
<style>
  /* Style untuk bubble/pill kriteria yang dipilih */
  .criteria-pill {
    display: inline-flex;
    align-items: center;
    background-color: #e9ecef;
    border: 1px solid #dee2e6;
    border-radius: 50px;
    padding: 0.25rem 0.75rem;
    font-size: 0.9rem;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
  }

  .criteria-pill .remove-btn {
    background: none;
    border: none;
    font-size: 1.2rem;
    line-height: 1;
    margin-left: 0.5rem;
    padding: 0;
    color: #6c757d;
  }

  .criteria-pill .remove-btn:hover {
    color: #dc3545;
  }
</style>
@endpush

@section('content')
<section class="py-5 bg-light min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card shadow rounded-4 border-0">
          <div class="card-body p-4 p-md-5">
            <h2 class="fw-bold mb-4 text-center">Formulir Permohonan Data</h2>
            <p class="text-muted text-center mb-4">Silakan isi data permohonan dengan lengkap dan benar.</p>

            <form action="{{ route('permohonan.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row g-4">
                <div class="col-12"><label class="form-label fw-semibold mb-2">Tujuan Permohonan Data</label>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-check"><input class="form-check-input" type="radio" name="tujuan" id="tujuan_kebijakan" value="Bahan perencanaan dan penyusunan kebijakan" required {{ old('tujuan') == 'Bahan perencanaan dan penyusunan kebijakan' ? 'checked' : '' }}><label class="form-check-label" for="tujuan_kebijakan">Bahan perencanaan dan penyusunan kebijakan</label></div>
                      <div class="form-check"><input class="form-check-input" type="radio" name="tujuan" id="tujuan_publikasi" value="Bahan publikasi" {{ old('tujuan') == 'Bahan publikasi' ? 'checked' : '' }}><label class="form-check-label" for="tujuan_publikasi">Bahan publikasi</label></div>
                      <div class="form-check"><input class="form-check-input" type="radio" name="tujuan" id="tujuan_monev" value="Bahan monitoring dan evaluasi" {{ old('tujuan') == 'Bahan monitoring dan evaluasi' ? 'checked' : '' }}><label class="form-check-label" for="tujuan_monev">Bahan monitoring dan evaluasi</label></div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-check"><input class="form-check-input" type="radio" name="tujuan" id="tujuan_penelitian" value="Bahan penelitian" {{ old('tujuan') == 'Bahan penelitian' ? 'checked' : '' }}><label class="form-check-label" for="tujuan_penelitian">Bahan penelitian</label></div>
                      <div class="form-check"><input class="form-check-input" type="radio" name="tujuan" id="tujuan_paparan" value="Bahan paparan" {{ old('tujuan') == 'Bahan paparan' ? 'checked' : '' }}><label class="form-check-label" for="tujuan_paparan">Bahan paparan</label></div>
                      <div class="form-check"><input class="form-check-input" type="radio" name="tujuan" id="tujuan_lainnya" value="Lainnya" {{ old('tujuan') == 'Lainnya' ? 'checked' : '' }}><label class="form-check-label" for="tujuan_lainnya">Lainnya</label></div>
                    </div>
                  </div>
                  <div id="tujuanLainnyaContainer" class="mt-2 d-none"><input type="text" class="form-control rounded-3" name="tujuan_lainnya_text" placeholder="Sebutkan tujuan lainnya di sini" value="{{ old('tujuan_lainnya_text') }}"></div>
                </div>

                <div class="col-12"><label class="form-label fw-semibold">Tipe File yang diperlukan</label>
                  <div class="d-flex gap-4">
                    <div class="form-check"><input class="form-check-input" type="radio" name="tipe" id="pdf" value="pdf" required {{ old('tipe') == 'pdf' ? 'checked' : '' }}><label class="form-check-label" for="pdf">PDF</label></div>
                    <div class="form-check"><input class="form-check-input" type="radio" name="tipe" id="excel" value="excel" {{ old('tipe') == 'excel' ? 'checked' : '' }}><label class="form-check-label" for="excel">Excel</label></div>
                    <div class="form-check"><input class="form-check-input" type="radio" name="tipe" id="csv" value="csv" {{ old('tipe') == 'csv' ? 'checked' : '' }}><label class="form-check-label" for="csv">CSV</label></div>
                  </div>
                </div>

                <div class="col-12"><label class="form-label fw-semibold">Asal Instansi/Lembaga</label>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-check"><input class="form-check-input" type="radio" name="asal" id="pemerintahan" value="pemerintahan" required {{ old('asal') == 'pemerintahan' ? 'checked' : '' }}><label class="form-check-label" for="pemerintahan">Kementerian/Lembaga Pemerintah</label></div>
                      <div class="form-check"><input class="form-check-input" type="radio" name="asal" id="akademisi" value="akademisi" {{ old('asal') == 'akademisi' ? 'checked' : '' }}><label class="form-check-label" for="akademisi">Akademisi/Mahasiswa/Pelajar</label></div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-check"><input class="form-check-input" type="radio" name="asal" id="swasta" value="swasta" {{ old('asal') == 'swasta' ? 'checked' : '' }}><label class="form-check-label" for="swasta">Swasta</label></div>
                    </div>
                  </div>
                </div>

                <div class="col-12">
                <label class="form-label fw-semibold">Data yang dibutuhkan</label>
                
                {{-- Bagian Filter Builder --}}
                <div id="filter-builder-section">
                    <div class="row g-2 mb-2">
                        <div class="col-md-5"><label for="pilihKriteria" class="small text-muted d-block mb-1">Pilih Kriteria</label><select id="pilihKriteria" class="form-select"><option value="" selected>-- Pilih --</option>@foreach (array_keys($kriteria) as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach</select></div>
                        <div class="col-md-5"><label for="pilihNilai" class="small text-muted d-block mb-1">Pilih Nilai</label><select id="pilihNilai" class="form-select" disabled><option value="">--</option></select></div>
                        <div class="col-md-2 d-flex align-items-end"><button type="button" id="tambahKriteria" class="btn btn-primary w-100">Tambah</button></div>
                    </div>
                    <div id="kriteria-pills-container" class="border rounded-3 p-3 mt-2" style="min-height: 80px;"></div>
                    {{-- Input tersembunyi dengan nama spesifik --}}
                    <input type="hidden" name="jenis_data_kriteria" id="kriteriaJsonInput" value="{{ old('jenis_data_kriteria', '[]') }}">
                    @error('jenis_data_kriteria')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                
                {{-- Textbox untuk kriteria khusus --}}
                <div id="kriteria-khusus-section" class="d-none">
                    {{-- Textarea dengan nama spesifik --}}
                    <textarea class="form-control rounded-3 @error('jenis_data_kustom') is-invalid @enderror" id="kriteriaKustomTextarea" name="jenis_data_kustom" rows="4" placeholder="Jelaskan secara rinci data yang Anda butuhkan...">{{ old('jenis_data_kustom') }}</textarea>
                    @error('jenis_data_kustom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                {{-- Checkbox untuk beralih mode --}}
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="kriteriaKhususCheckbox" name="is_kustom" {{ old('is_kustom') ? 'checked' : '' }}>
                    <label class="form-check-label" for="kriteriaKhususCheckbox">
                        Saya ingin menuliskan sendiri kriteria data yang dibutuhkan
                    </label>
                </div>
            </div>

                <div class="col-12">
                  <label class="form-label fw-semibold">Pilih Kolom Data yang Ingin Ditampilkan</label>
                  <p class="text-muted small">Pilih data spesifik yang Anda butuhkan. Ini akan menjadi kolom pada file Excel/CSV yang Anda terima.</p>

                  <div class="accordion" id="kolomAccordion">

                    {{-- Grup 1: Data Pribadi --}}
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingPribadi">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePribadi">
                          <i class="fas fa-user-circle me-2"></i> Data Pribadi
                        </button>
                      </h2>
                      <div id="collapsePribadi" class="accordion-collapse collapse" data-bs-parent="#kolomAccordion">
                        <div class="accordion-body">
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-check"><input class="form-check-input" type="checkbox" name="kolom[]" value="nama" id="kolom_nama"> <label class="form-check-label" for="kolom_nama">Nama Lengkap</label></div>
                              <div class="form-check"><input class="form-check-input" type="checkbox" name="kolom[]" value="nik" id="kolom_nik"> <label class="form-check-label" for="kolom_nik">NIK</label></div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-check"><input class="form-check-input" type="checkbox" name="kolom[]" value="agama" id="kolom_agama"> <label class="form-check-label" for="kolom_agama">Agama</label></div>
                              <div class="form-check"><input class="form-check-input" type="checkbox" name="kolom[]" value="jenisKelamin" id="kolom_gender"> <label class="form-check-label" for="kolom_gender">Jenis Kelamin</label></div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-check"><input class="form-check-input" type="checkbox" name="kolom[]" value="alamat" id="kolom_alamat"> <label class="form-check-label" for="kolom_alamat">Alamat</label></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    {{-- Grup 2: Data Kepegawaian --}}
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingKepegawaian">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKepegawaian">
                          <i class="fas fa-id-card me-2"></i> Data Kepegawaian
                        </button>
                      </h2>
                      <div id="collapseKepegawaian" class="accordion-collapse collapse" data-bs-parent="#kolomAccordion">
                        <div class="accordion-body">
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-check"><input class="form-check-input" type="checkbox" name="kolom[]" value="nipBaru" id="kolom_nip"> <label class="form-check-label" for="kolom_nip">NIP Baru</label></div>
                              <div class="form-check"><input class="form-check-input" type="checkbox" name="kolom[]" value="statusPegawai" id="kolom_status"> <label class="form-check-label" for="kolom_status">Status Pegawai</label></div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-check"><input class="form-check-input" type="checkbox" name="kolom[]" value="masaKerjaTahun" id="kolom_masa_kerja_thn"> <label class="form-check-label" for="kolom_masa_kerja_thn">Masa Kerja (Tahun)</label></div>
                              <div class="form-check"><input class="form-check-input" type="checkbox" name="kolom[]" value="masaKerjaBulan" id="kolom_masa_kerja_bln"> <label class="form-check-label" for="kolom_masa_kerja_bln">Masa Kerja (Bulan)</label></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    {{-- Grup 3: Data Jabatan & Pendidikan --}}
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingJabatan">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseJabatan">
                          <i class="fas fa-graduation-cap me-2"></i> Data Jabatan & Pendidikan
                        </button>
                      </h2>
                      <div id="collapseJabatan" class="accordion-collapse collapse" data-bs-parent="#kolomAccordion">
                        <div class="accordion-body">
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-check"><input class="form-check-input" type="checkbox" name="kolom[]" value="jabatanNama" id="kolom_jabatan"> <label class="form-check-label" for="kolom_jabatan">Nama Jabatan</label></div>
                              <div class="form-check"><input class="form-check-input" type="checkbox" name="kolom[]" value="satuanKerjaKerjaNama" id="kolom_departemen"> <label class="form-check-label" for="kolom_departemen">Departemen/Unit Kerja</label></div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-check"><input class="form-check-input" type="checkbox" name="kolom[]" value="golRuangAkhir" id="kolom_golongan"> <label class="form-check-label" for="kolom_golongan">Golongan</label></div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-check"><input class="form-check-input" type="checkbox" name="kolom[]" value="pendidikanTerakhirNama" id="kolom_pendidikan"> <label class="form-check-label" for="kolom_pendidikan">Pendidikan Terakhir</label></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>

                <div class="col-12"><label for="file_permohonan" class="form-label fw-semibold">Surat Pengantar (PDF/DOC, Max 2MB)</label><input type="file" class="form-control rounded-3 @error('file_permohonan') is-invalid @enderror" id="file_permohonan" name="file_permohonan">@error('file_permohonan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-12"><label for="catatan" class="form-label fw-semibold">Catatan Tambahan</label><textarea class="form-control rounded-3" id="catatan" name="catatan" rows="3" placeholder="Tambahkan keterangan tambahan jika diperlukan.">{{ old('catatan') }}</textarea></div>

                <div class="col-12 mt-4"><button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold">Kirim Permohonan</button></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Ambil data kriteria dari PHP ke JavaScript
    const kriteriaData = @json($kriteria ?? []);

    // Elemen-elemen untuk Filter Builder
    const kriteriaSelect = document.getElementById('pilihKriteria');
    const nilaiSelect = document.getElementById('pilihNilai');
    const tambahBtn = document.getElementById('tambahKriteria');
    const pillsContainer = document.getElementById('kriteria-pills-container');

    // Elemen-elemen untuk beralih mode
    const kriteriaKhususCheckbox = document.getElementById('kriteriaKhususCheckbox');
    const filterBuilderSection = document.getElementById('filter-builder-section');
    const kriteriaKhususSection = document.getElementById('kriteria-khusus-section');

    // Input yang akan dikirim ke backend (keduanya punya name="jenis_data")
    const kriteriaJsonInput = document.getElementById('kriteriaJsonInput');
    const kriteriaKustomTextarea = document.getElementById('kriteriaKustomTextarea');

    // Variabel untuk menyimpan kriteria yang dipilih
    let selectedCriteria = [];
    try {
      // Coba parsing data lama jika ada (misal: setelah validation error)
      let oldData = JSON.parse(kriteriaJsonInput.value);
      if (Array.isArray(oldData)) {
        selectedCriteria = oldData;
      }
    } catch (e) {
      selectedCriteria = [];
    }

    // --- FUNGSI-FUNGSI UTAMA ---

    // Fungsi untuk menampilkan bubble/pill kriteria
    const renderPills = () => {
      pillsContainer.innerHTML = '';
      selectedCriteria.forEach(item => {
        const pill = document.createElement('span');
        pill.className = 'criteria-pill';
        pill.innerHTML = `<span class="text-muted small">${item.kriteria}:</span>&nbsp;${item.nilai}<button type="button" class="remove-btn" data-kriteria="${item.kriteria}" data-nilai="${item.nilai}">&times;</button>`;
        pillsContainer.appendChild(pill);
      });
      kriteriaJsonInput.value = selectedCriteria.length > 0 ? JSON.stringify(selectedCriteria) : '';
    };

    // Fungsi untuk beralih antara Filter Builder dan Textbox Kustom
    const toggleInputMode = () => {
      if (kriteriaKhususCheckbox.checked) {
        filterBuilderSection.classList.add('d-none');
        kriteriaKhususSection.classList.remove('d-none');
        kriteriaJsonInput.disabled = true; // Nonaktifkan input JSON
        kriteriaKustomTextarea.disabled = false; // Aktifkan textarea
      } else {
        filterBuilderSection.classList.remove('d-none');
        kriteriaKhususSection.classList.add('d-none');
        kriteriaJsonInput.disabled = false; // Aktifkan input JSON
        kriteriaKustomTextarea.disabled = true; // Nonaktifkan textarea
      }
    };

    // --- EVENT LISTENERS ---

    // Saat dropdown Kriteria berubah
    kriteriaSelect.addEventListener('change', function() {
      const selectedKey = this.value;
      const nilaiOptions = kriteriaData[selectedKey] || [];

      nilaiSelect.innerHTML = '<option value="" selected>-- Pilih --</option>';
      if (nilaiOptions.length > 0) {
        nilaiOptions.forEach(nilai => {
          nilaiSelect.innerHTML += `<option value="${nilai}">${nilai}</option>`;
        });
        nilaiSelect.disabled = false;
      } else {
        nilaiSelect.disabled = true;
      }
    });

    // Saat tombol "Tambah" diklik
    tambahBtn.addEventListener('click', function() {
      const kriteria = kriteriaSelect.value;
      const nilai = nilaiSelect.value;

      // Pengecekan yang lebih andal
      if (kriteria && nilai) {
        const isExist = selectedCriteria.some(item => item.kriteria === kriteria && item.nilai === nilai);
        if (!isExist) {
          selectedCriteria.push({
            kriteria,
            nilai
          });
          renderPills();
        } else {
          alert('Kriteria tersebut sudah ditambahkan.');
        }
      } else {
        alert('Silakan pilih kriteria dan nilai terlebih dahulu.');
      }
    });

    // Saat tombol 'x' pada bubble diklik
    pillsContainer.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('remove-btn')) {
        const kriteria = e.target.dataset.kriteria;
        const nilai = e.target.dataset.nilai;
        selectedCriteria = selectedCriteria.filter(item => !(item.kriteria === kriteria && item.nilai === nilai));
        renderPills();
      }
    });

    // Saat checkbox kriteria khusus diubah
    kriteriaKhususCheckbox.addEventListener('change', toggleInputMode);

    // --- INISIALISASI ---
    renderPills(); // Render bubble/pill yang mungkin ada dari data lama
    toggleInputMode(); // Atur tampilan awal berdasarkan status checkbox
  });
</script>
@endpush