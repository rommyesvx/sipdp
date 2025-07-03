@extends('layouts.admin')

@section('title', 'Detail Permohonan #' . $permohonan->id)

@push('styles')
{{-- Style kustom untuk beberapa elemen agar sesuai desain --}}
<style>
    .request-header-gradient {
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    }

    /* Style untuk Tombol Status Kustom */
    .btn-status-option {
        text-align: left;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 0.75rem;
        border: 2px solid transparent;
        transition: all 0.2s ease-in-out;
    }

    .btn-status-option .icon-circle {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-right: 1rem;
        color: #fff;
    }

    .btn-status-option.btn-approve {
        background-color: #e0f8f0;
        color: #146c43;
    }

    .btn-status-option.btn-approve .icon-circle {
        background-color: #198754;
    }

    .btn-status-option.btn-approve.active,
    .btn-status-option.btn-approve:hover {
        border-color: #198754;
    }

    .btn-status-option.btn-reject {
        background-color: #fdeeee;
        color: #b02a37;
    }

    .btn-status-option.btn-reject .icon-circle {
        background-color: #dc3545;
    }

    .btn-status-option.btn-reject.active,
    .btn-status-option.btn-reject:hover {
        border-color: #dc3545;
    }

    .btn-status-option.btn-escalate {
        background-color: #e7e7ff;
        color: #6759ff;
    }

    .btn-status-option.btn-escalate .icon-circle {
        background-color: #6759ff;
    }

    .btn-status-option.btn-escalate.active,
    .btn-status-option.btn-escalate:hover {
        border-color: #6759ff;
    }

    /* Struktur Timeline */
    .timeline {
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 5px;
        bottom: 5px;
        width: 3px;
        background-color: #e9ecef;
        border-radius: 3px;
    }

    .timeline-step {
        position: relative;
        padding-left: 32px;
        margin-bottom: 2rem;
    }

    .timeline-step:last-child {
        margin-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: 0;
        top: 5px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: var(--bs-body-bg);
        border: 3px solid #ced4da;
    }

    .timeline-step.completed .timeline-dot {
        border-color: var(--bs-primary);
    }

    .timeline-step.active .timeline-dot {
        background-color: var(--bs-success);
        border-color: var(--bs-success);
        box-shadow: 0 0 0 4px rgba(25, 135, 84, .25);
    }

    .timeline-step.escalated .timeline-dot {
        background-color: #6759ff;
        border-color: #6759ff;
        box-shadow: 0 0 0 4px rgba(103, 89, 255, .25);
    }

    .timeline-step.rejected .timeline-dot {
        background-color: var(--bs-danger);
        border-color: var(--bs-danger);
        box-shadow: 0 0 0 4px rgba(220, 53, 69, .25);
    }

    .star-rating i {
        cursor: pointer;
        transition: color 0.2s ease;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0 text-gray-800">Detail Permohonan</h1>
        <a href="{{ route('admin.permohonan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 mb-4 detail-card">
                <div class="card-header py-3">
                    <h5 class="m-0 fw-bold text-primary">Data Pemohon</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Nama Pemohon</dt>
                        <dd class="col-sm-8">{{ $permohonan->user->name }}</dd>

                        <dt class="col-sm-4">Asal Instansi</dt>
                        <dd class="col-sm-8 text-capitalize">{{ $permohonan->asal }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 mb-4 detail-card">
                <div class="card-header py-3">
                    <h5 class="m-0 fw-bold text-primary">Detail Permintaan</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Tanggal Diajukan</dt>
                        <dd class="col-sm-8">{{ $permohonan->created_at->format('d M Y, H:i') }}</dd>

                        <dt class="col-sm-4">Tujuan Permohonan</dt>
                        <dd class="col-sm-8">{{ $permohonan->tujuan }}</dd>

                        @if ($permohonan->tujuan == 'Lainnya' && $permohonan->tujuan_lainnya_text)
                        <dt class="col-sm-4">Tujuan Lainnya</dt>
                        <dd class="col-sm-8"><em>{{ $permohonan->tujuan_lainnya_text }}</em></dd>
                        @endif

                        <dt class="col-sm-4">Jenis Data Diminta</dt>
                        <dd class="col-sm-8">{!! $permohonan->formatted_jenis_data !!}</dd>

                        <dt class="col-sm-4">Kolom Data Diminta</dt>
                        <dd class="col-sm-8">
                            @if(!empty($permohonan->kolom_diminta) && \Illuminate\Support\Str::isJson($permohonan->kolom_diminta))
                            <div class="mb-4">
                                <div>
                                    @php
                                    // Decode string JSON menjadi array
                                    $kolomList = json_decode($permohonan->kolom_diminta, true);

                                    $columnLabels = [
                                    'nama' => 'Nama Lengkap', 'nipBaru' => 'NIP Baru', 'nik' => 'NIK',
                                    'agama' => 'Agama', 'jenisKelamin' => 'Jenis Kelamin', 'alamat' => 'Alamat',
                                    'statusPegawai' => 'Status Pegawai', 'masaKerjaTahun' => 'Masa Kerja (Tahun)',
                                    'masaKerjaBulan' => 'Masa Kerja (Bulan)', 'jabatanNama' => 'Nama Jabatan',
                                    'satuanKerjaKerjaNama' => 'Departemen', 'golRuangAkhir' => 'Golongan',
                                    'pendidikanTerakhirNama' => 'Pendidikan Terakhir',
                                    ];
                                    @endphp

                                    @if(is_array($kolomList) && !empty($kolomList))
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($kolomList as $kolom)
                                        <span class="badge text-dark-emphasis bg-light-subtle border fw-medium">
                                            {{-- Tampilkan label dari kamus, atau nama kolom asli jika tidak ada --}}
                                            {{ $columnLabels[$kolom] ?? ucfirst(str_replace('_', ' ', $kolom)) }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Tipe File</dt>
                        <dd class="col-sm-8 text-uppercase">{{ $permohonan->tipe }}</dd>

                        @if ($permohonan->catatan)
                        <dt class="col-sm-4">Catatan Tambahan</dt>
                        <dd class="col-sm-8">{{ $permohonan->catatan }}</dd>
                        @endif

                        @if ($permohonan->file_permohonan)
                        <dt class="col-sm-4">Surat Pengantar</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('admin.permohonan.downloadSurat', ['id' => $permohonan->id]) }}" target="_blank" class="text-decoration-none">
                                <i class="fas fa-file-pdf text-danger"></i> Lihat Surat
                            </a>
                        </dd>
                        @endif
                    </dl>
                    @if (!\Illuminate\Support\Str::isJson($permohonan->jenis_data))
                    <div class="alert alert-info">
                        <h5 class="alert-heading fw-bold"><i class="fas fa-info-circle me-2"></i>Permohonan Kustom</h5>
                        <p class="mb-0">Ini adalah permohonan dengan kriteria khusus. Silakan siapkan data secara manual dan unggah hasilnya melalui panel tindakan di sebelah kanan.</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 detail-card">
                <div class="card-header bg-light-subtle py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-primary">Preview Data yang Diminta</h5>
                    @if ($pegawaisPreview->isNotEmpty())
                    <div>
                        <a href="{{ route('admin.permohonan.export', ['id' => $permohonan->id, 'tipe' => 'pdf']) }}" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf me-2"></i>Export PDF</a>
                        <a href="{{ route('admin.permohonan.export', ['id' => $permohonan->id, 'tipe' => 'excel']) }}" class="btn btn-outline-success btn-sm"><i class="fas fa-file-excel me-2"></i>Export Excel</a>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    @if ($pegawaisPreview->isNotEmpty())
                    <p class="text-muted small">Tabel di bawah ini adalah preview dari 10 data pertama yang cocok dengan kriteria yang diajukan.</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    @foreach($kolomTampil as $kolom)
                                    <th>{{ $columnLabels[$kolom] ?? ucfirst($kolom) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pegawaisPreview as $pegawai)
                                <tr>
                                    @foreach($kolomTampil as $kolom)
                                    <td>{{ $pegawai->{$kolom} }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted p-4">
                        <p class="mb-1 fw-bold">Preview Tidak Tersedia</p>
                        <small>Permohonan ini diajukan dengan kriteria khusus atau tidak ada data pegawai yang cocok.</small>
                    </div>
                    {{-- Menampilkan kriteria kustom jika ada --}}
                    @if (!\Illuminate\Support\Str::isJson($permohonan->jenis_data))
                    <div class="bg-body-tertiary border rounded-3 p-3 mt-2">
                        <p class="mb-1 small text-muted">Kriteria Kustom yang Ditulis Pengguna:</p>
                        <blockquote class="mb-0">"{!! nl2br(e($permohonan->jenis_data)) !!}"</blockquote>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
            <hr>

            @if ($permohonan->status == 'selesai' && $permohonan->feedback)
            <div class="card shadow-sm border-0 rounded-4 mb-4 detail-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                        <h5 class="m-0 fw-bold text-primary">Feedback dari pemohon</h5>
                        <div class="text-end">
                            <span class="text-warning h5 mb-0">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $permohonan->feedback->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                            </span>
                            <span class="fw-bold ms-2">{{ number_format($permohonan->feedback->rating, 1) }}</span>
                        </div>
                    </div>

                    @if ($permohonan->feedback->pesan)
                    <p class="fst-italic text-muted">"{{ $permohonan->feedback->pesan }}"</p>
                    @else
                    <p class="fst-italic text-muted">Pengguna tidak meninggalkan pesan.</p>
                    @endif

                    <hr>

                    @if ($permohonan->feedback->catatan_evaluasi)
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-clipboard-check me-2"></i> Evaluasi
                            </h6>
                            <span class="badge bg-success-subtle text-success-emphasis rounded-pill">
                                <i class="fas fa-check me-1"></i> Sudah Dievaluasi
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Catatan Evaluasi :</label>
                            <div class="p-3 bg-light border rounded-3">
                                <p class="mb-0">{{ $permohonan->feedback->catatan_evaluasi }}</p>
                            </div>
                        </div>
                    </div>

                    @else

                    {{-- TAMPILAN JIKA BELUM ADA CATATAN (FORM INPUT) --}}
                    <form action="{{ route('feedback.updateEvaluasi', $permohonan->feedback->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-clipboard-check me-2"></i> Evaluasi
                            </h6>
                            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill">
                                <i class="fas fa-clock me-1"></i> Belum Dievaluasi
                            </span>
                        </div>

                        <div class="mb-3">
                            <label for="catatan_evaluasi" class="form-label small">Catatan Evaluasi :</label>
                            {{-- Tambahkan class is-invalid jika ada error --}}
                            <textarea class="form-control bg-light @error('catatan_evaluasi') is-invalid @enderror" id="catatan_evaluasi" name="catatan_evaluasi" rows="4" placeholder="Tulis catatan atau tindak lanjut dari feedback ini..." required>{{ old('catatan_evaluasi', $permohonan->feedback->catatan_evaluasi) }}</textarea>

                            {{-- Blok untuk menampilkan pesan error validasi --}}
                            @error('catatan_evaluasi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary rounded-pill">
                                <i class="fas fa-save me-1"></i> Simpan Evaluasi
                            </button>
                        </div>
                    </form>

                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Panel Aksi --}}
        <div class="col-lg-4">
            @if(in_array($permohonan->status, ['selesai', 'ditolak', 'eskalasi']))
            {{-- TAMPILAN JIKA STATUS SUDAH FINAL / DI TANGAN KEPALA BIDANG --}}
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold d-flex align-items-center mb-3"><i class="fas fa-info-circle me-2 text-muted"></i> Status Permohonan</h5>
                    @if ($permohonan->status == 'selesai')
                    <div class="alert alert-success border-0 border-start border-5 border-success">
                        <div class="d-flex align-items-center"><i class="fas fa-check-circle fs-4 me-3"></i>
                            <div>
                                <p class="fw-bold mb-0">Permohonan disetujui</p>
                                <p class="mb-0 small">Data hasil telah dikirim.</p>
                            </div>
                        </div>
                    </div>
                    @if($permohonan->file_hasil)
                    <div class="mt-3"><label class="form-label small fw-semibold">File Hasil Terlampir:</label><a href="{{ route('admin.permohonan.index', ['id' => $permohonan->id]) }}" class="text-decoration-none">
                            <div class="border border-2 border-dashed rounded-3 p-3 d-flex align-items-center gap-3">
                                <div class="fs-2 text-success"><i class="fas fa-file-alt"></i></div>
                                <div class="flex-grow-1">
                                    <p class="fw-semibold mb-0 text-break">{{ basename($permohonan->file_hasil) }}</p><small class="text-primary">Unduh File</small>
                                </div>
                            </div>
                        </a></div>
                    @endif
                    @elseif ($permohonan->status == 'ditolak')
                    <div class="alert alert-danger border-0 border-start border-5 border-danger">
                        <div class="d-flex align-items-center"><i class="fas fa-times-circle fs-4 me-3"></i>
                            <div>
                                <p class="fw-bold mb-0">Permohonan ditolak</p>
                            </div>
                        </div>
                    </div>
                    @if($permohonan->alasan_penolakan)<div class="mt-3"><label class="form-label small fw-semibold">Alasan Penolakan:</label>
                        <div class="p-3 bg-light border rounded-3">
                            <p class="mb-0 fst-italic">"{{ $permohonan->alasan_penolakan }}"</p>
                        </div>
                    </div>@endif
                    @elseif ($permohonan->status == 'eskalasi')
                    <div class="alert border-0 border-start border-5" style="border-color: #6759ff!important; background-color: #e7e7ff;">
                        <div class="d-flex align-items-center"><i class="fas fa-level-up-alt fs-4 me-3" style="color: #6759ff;"></i>
                            <div>
                                <p class="fw-bold mb-0" style="color: #6759ff;">Sedang Dalam Eskalasi</p>
                                <p class="mb-0 small">Permohonan sedang ditinjau oleh Kepala Bidang.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @else {{-- JIKA STATUS MASIH AKTIF (menunggu, diproses, sudah eskalasi), tampilkan form aksi --}}
            <div class="card shadow-sm border-0 rounded-4 position-sticky top-0 action-card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.permohonan.update', $permohonan->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-edit fs-4 me-3 text-primary"></i>
                            <h5 class="m-0 fw-bold">Ubah Status Permohonan</h5>
                        </div>

                        @if ($permohonan->status == 'sudah eskalasi' && $permohonan->catatan_kepala_bidang)
                        <div class="alert alert-info small mb-3"><strong class="d-block">Catatan dari Kepala Bidang:</strong>"{{ $permohonan->catatan_kepala_bidang }}"</div>
                        @endif

                        <input type="hidden" name="status" id="statusInput">

                        {{-- Tampilkan tombol-tombol ini HANYA JIKA status BUKAN 'sudah eskalasi' --}}
                        @if ($permohonan->status != 'sudah eskalasi')
                        <p class="small text-muted">Pilih salah satu tindakan di bawah ini.</p>
                        <div id="status-buttons-container" class="d-grid gap-3 mb-3">
                            <button type="button" class="btn btn-status-option btn-approve" data-status="selesai"><span class="icon-circle"><i class="fas fa-check"></i></span> Setujui Permohonan</button>
                            <button type="button" class="btn btn-status-option btn-reject" data-status="ditolak"><span class="icon-circle"><i class="fas fa-times"></i></span> Tolak Permohonan</button>
                            @if(in_array($permohonan->status, ['diajukan', 'diproses']))
                            <button type="button" class="btn btn-status-option btn-escalate" data-status="eskalasi"><span class="icon-circle"><i class="fas fa-level-up-alt"></i></span> Eskalasi Permohonan</button>
                            @endif
                        </div>
                        <div id="selected-status-info" class="d-none mb-3">
                            <p class="text-muted mb-1">Status dipilih:</p>
                            <div class="alert alert-primary d-flex justify-content-between align-items-center py-2 px-3"><strong id="selected-status-text" class="text-capitalize"></strong><button type="button" id="cancel-selection-btn" class="btn-close" title="Ubah Pilihan"></button></div>
                        </div>
                        @endif

                        {{-- Form dinamis yang akan muncul/hilang --}}
                        <div id="dynamic-form-fields" class="{{ $permohonan->status == 'sudah eskalasi' ? '' : 'd-none' }}">
                            <hr class="{{ $permohonan->status == 'sudah eskalasi' ? 'd-none' : '' }}">

                            <div id="formSelesai" class="{{ $permohonan->status == 'sudah eskalasi' ? '' : 'd-none' }}">
                                <div class="mb-3"><label for="file_hasil" class="form-label fw-semibold">Upload Hasil Data <span class="text-danger">*</span></label><input class="form-control @error('file_hasil') is-invalid @enderror" type="file" name="file_hasil">
                                    <div class="invalid-feedback">@error('file_hasil') {{ $message }} @enderror</div>
                                </div>
                                <div class="mb-3"><label for="catatan_selesai" class="form-label fw-semibold">Catatan Tambahan (Opsional)</label><textarea name="catatan_selesai" class="form-control" rows="3" placeholder="Contoh: Data terlampir..."></textarea></div>
                            </div>
                            <div id="formDitolak" class="d-none">
                                <div class="mb-3"><label for="alasan_penolakan" class="form-label fw-semibold text-danger">Alasan Penolakan</label><textarea name="alasan_penolakan" class="form-control @error('alasan_penolakan') is-invalid @enderror" rows="4" placeholder="Jelaskan mengapa..."></textarea>
                                    <div class="invalid-feedback">@error('alasan_penolakan') {{ $message }} @enderror</div>
                                </div>
                            </div>
                            <div id="formEskalasi" class="d-none">
                                <div class="mb-3"><label for="alasan_eskalasi" class="form-label fw-semibold text-primary">Alasan Eskalasi</label><textarea name="alasan_eskalasi" class="form-control @error('alasan_eskalasi') is-invalid @enderror" rows="4" placeholder="Jelaskan mengapa..."></textarea>
                                    <div class="invalid-feedback">@error('alasan_eskalasi') {{ $message }} @enderror</div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol submit hanya muncul jika ada aksi yang bisa dilakukan --}}
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary fw-bold py-2">Kirim Update</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const actionCard = document.querySelector('.action-card');
        if (actionCard) {
            const statusButtons = actionCard.querySelectorAll('.btn-status-option');
            const statusInput = actionCard.querySelector('#statusInput');
            const dynamicFormContainer = document.getElementById('dynamic-form-fields');
            const formSelesai = actionCard.querySelector('#formSelesai');
            const formDitolak = actionCard.querySelector('#formDitolak');
            const formEskalasi = actionCard.querySelector('#formEskalasi');
            const alasanPenolakanInput = actionCard.querySelector('[name="alasan_penolakan"]');
            const alasanEskalasiInput = actionCard.querySelector('[name="alasan_eskalasi"]');

            const updateFormVisibility = (selectedStatus) => {
                [formSelesai, formDitolak, formEskalasi].forEach(form => form.classList.add('d-none'));
                [alasanPenolakanInput, alasanEskalasiInput].forEach(input => input.removeAttribute('required'));
                statusButtons.forEach(btn => btn.classList.remove('active'));

                if (selectedStatus) {
                    dynamicFormContainer.classList.remove('d-none');
                    const activeButton = actionCard.querySelector(`.btn-status-option[data-status="${selectedStatus}"]`);
                    if (activeButton) {
                        activeButton.classList.add('active');
                    }

                    if (selectedStatus === 'selesai') {
                        formSelesai.classList.remove('d-none');
                        // File hasil menjadi wajib jika statusnya 'selesai'
                        actionCard.querySelector('[name="file_hasil"]').setAttribute('required', 'required');
                    } else {
                        actionCard.querySelector('[name="file_hasil"]').removeAttribute('required');
                    }

                    if (selectedStatus === 'ditolak') {
                        formDitolak.classList.remove('d-none');
                        alasanPenolakanInput.setAttribute('required', 'required');
                    } else if (selectedStatus === 'eskalasi') {
                        formEskalasi.classList.remove('d-none');
                        alasanEskalasiInput.setAttribute('required', 'required');
                    }
                } else {
                    dynamicFormContainer.classList.add('d-none');
                }
            };

            statusButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const newStatus = this.dataset.status;
                    if (statusInput.value === newStatus) {
                        statusInput.value = '';
                        updateFormVisibility('');
                    } else {
                        statusInput.value = newStatus;
                        updateFormVisibility(newStatus);
                    }
                });
            });

            // --- LOGIKA BARU UNTUK STATUS 'SUDAH ESKALASI' ---
            let initialStatus = "{{ $permohonan->status }}";
            if (initialStatus === 'sudah eskalasi') {
                // Jika statusnya 'sudah eskalasi', langsung set pilihan ke 'selesai'
                statusInput.value = 'selesai';
                updateFormVisibility('selesai');
            } else {
                updateFormVisibility('');
            }
        }
    });
</script>
@endpush