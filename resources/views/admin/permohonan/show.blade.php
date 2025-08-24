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

    .info-card-eskalasi {
        background-color: #eef2ff; 
        border-left: 5px solid #6366f1; 
        color: #4338ca; 
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
                    <h5 class="m-0 fw-bold text-primary">Informasi Permohonan</h5>
                </div>
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center py-4">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-2" style="width:70px;height:70px;font-size:2rem;font-weight:700;color:#6759ff;">
                                {{ strtoupper(Str::substr($permohonan->user->name,0,2)) }}
                            </div>
                            <div class="text-center">
                                <div class="fw-bold fs-5 mb-1">{{ $permohonan->user->name }}</div>
                                <div class="text-muted small">{{ $permohonan->user->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-8 py-4">
                            <div class="row mb-2">
                                <div class="col-sm-6 mb-2">
                                    <span class="text-muted">Tujuan Permohonan</span>
                                    <div class="fw-bold">{{ $permohonan->tujuan }}</div>
                                    @if ($permohonan->tujuan == 'Lainnya' && $permohonan->tujuan_lainnya_text)
                                    <div class="fst-italic small">{{ $permohonan->tujuan_lainnya_text }}</div>
                                    @endif
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <span class="text-muted">Tipe File</span>
                                    <div class="fw-bold text-uppercase">{{ $permohonan->tipe }}</div>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <span class="text-muted">Instansi/Lembaga</span>
                                    <div class="fw-bold">{{ $permohonan->asal }}</div>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <span class="text-muted">Tanggal Diajukan</span>
                                    <div class="fw-bold">{{ $permohonan->created_at->format('d M Y, H:i') }} WIB</div>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <span class="text-muted">Jenis Data Diminta</span>
                                    <div>{!! $permohonan->formatted_jenis_data !!}</div>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <span class="text-muted">Kolom Data Diminta</span>
                                    @if(!empty($permohonan->kolom_diminta) && \Illuminate\Support\Str::isJson($permohonan->kolom_diminta))
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @php
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
                                            @foreach($kolomList as $kolom)
                                            <span class="badge text-dark-emphasis bg-light-subtle border fw-medium">
                                                {{ $columnLabels[$kolom] ?? ucfirst(str_replace('_', ' ', $kolom)) }}
                                            </span>
                                            @endforeach
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                @if ($permohonan->catatan)
                                <div class="col-sm-12 mb-2">
                                    <span class="text-muted">Catatan Tambahan</span>
                                    <div>{{ $permohonan->catatan }}</div>
                                </div>
                                @endif
                                @if ($permohonan->suratPengantar)
                                <div class="col-sm-12 mb-2">
                                    <span class="text-muted">Surat Pengantar</span>
                                    <div class="mt-2">
                                        <div class="border border-2 border-dashed rounded-3 p-3 d-flex align-items-center gap-3 bg-light-subtle" style="max-width:350px;">
                                            <div class="fs-2 text-primary"><i class="fas fa-file-pdf"></i></div>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold text-break">{{ $permohonan->suratPengantar->nama_asli_file }}</div>
                                            </div>
                                            <a href="{{ route('admin.permohonan.downloadSurat', ['id' => $permohonan->id]) }}" target="_blank" class="btn btn-primary btn-sm rounded-pill d-flex align-items-center gap-1">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (!\Illuminate\Support\Str::isJson($permohonan->jenis_data))
                    <div class="alert alert-info mt-4">
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
                    @if ($pegawaisPreview->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-center">
                            {{ $pegawaisPreview->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
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
                    <form action="{{ route('admin.feedback.updateEvaluasi', $permohonan->feedback->id) }}" method="POST">
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
                            <textarea class="form-control bg-light @error('catatan_evaluasi') is-invalid @enderror" id="catatan_evaluasi" name="catatan_evaluasi" rows="4" placeholder="Tulis catatan atau tindak lanjut dari feedback ini...">{{ old('catatan_evaluasi', $permohonan->feedback->catatan_evaluasi) }}</textarea>

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
            @if($permohonan->status == 'sudah eskalasi')
            <div class="card shadow-sm border-0 info-card-eskalasi mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="fs-2 me-3">
                            <i class="fas fa-reply-all"></i>
                        </div>
                        <div>
                            <h5 class="card-title fw-bold mb-1">Dikembalikan ke Staff IT</h5>
                            <p class="card-text mb-0 small">Permohonan telah disetujui oleh atasan. Silakan lampirkan file hasil untuk dikirimkan ke pemohon.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif


            @if(in_array($permohonan->status, ['selesai', 'ditolak', 'eskalasi']))
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
                    @if($permohonan->fileHasil)
                    <div class="mt-3">
                        <label class="form-label small fw-semibold">File Hasil Terlampir:</label>
                        <div class="border border-2 border-dashed rounded-3 p-3 d-flex align-items-center gap-3">
                            <div class="fs-2 text-success"><i class="fas fa-file-alt"></i></div>
                            <div class="flex-grow-1">
                                <p class="fw-semibold mb-0 text-break">{{ basename($permohonan->fileHasil->nama_asli_file) }}</p>
                                <a href="{{ route('admin.permohonan.downloadHasil', $permohonan->id) }}" class="btn btn-sm btn-outline-primary mt-2" target="_blank">
                                    <i class="fas fa-download me-1"></i> Download File Hasil
                                </a>
                            </div>
                        </div>
                    </div>
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
                                <p class="mb-0 fst-italic">"{{ $permohonan->alasan_eskalasi }}"</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @else
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
                            <div class="alert alert-primary d-flex justify-content-between align-items-center py-2 px-3">
                                <strong id="selected-status-text" class="text-capitalize"></strong>
                                <button type="button" id="cancel-selection-btn" class="btn-close" title="Ubah Pilihan">
                                </button>
                            </div>
                        </div>
                        @endif

                        {{-- Form dinamis yang akan muncul/hilang --}}
                        <div id="dynamic-form-fields" class="{{ $permohonan->status == 'sudah eskalasi' ? '' : 'd-none' }}">
                            <hr class="{{ $permohonan->status == 'sudah eskalasi' ? 'd-none' : '' }}">

                            <div id="formSelesai" class="d-none">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Lampirkan File Hasil <span class="text-danger">*</span></label>

                                    @if (\Illuminate\Support\Str::isJson($permohonan->jenis_data))
                                    {{-- JIKA YA: Tampilkan opsi Generate & Attach Otomatis --}}
                                    <div id="auto-generate-section">
                                        <button type="button" id="btn-generate-attach" class="btn btn-outline-primary w-100 mb-2">
                                            <i class="fas fa-cogs me-2"></i> Generate & Lampirkan File Sesuai Preview
                                        </button>
                                        <div id="file-generated-info" class="d-none alert alert-success p-2 small"></div>
                                    </div>
                                    {{-- Sediakan juga opsi upload manual sebagai fallback --}}
                                    <div class="text-center my-2">
                                        <small><a href="#" id="toggle-upload-mode">atau, upload file secara manual</a></small>
                                    </div>
                                    @else
                                    {{-- JIKA TIDAK (Permohonan Kustom): Langsung tampilkan input upload manual --}}
                                    <div>
                                        <input class="form-control @error('file_hasil') is-invalid @enderror" type="file" name="file_hasil" required>
                                        <div class="form-text">Permohonan ini bersifat kustom, harap siapkan dan unggah file hasilnya secara manual.</div>
                                        @error('file_hasil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    @endif

                                    {{-- BAGIAN UNTUK UPLOAD MANUAL (TERSEMBUNYI) --}}
                                    <div id="manual-upload-section" class="d-none">
                                        <input class="form-control @error('file_hasil') is-invalid @enderror" type="file" name="file_hasil" disabled>
                                        @error('file_hasil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- Input tersembunyi untuk menyimpan path file --}}
                                    <input type="hidden" name="file_hasil_path" id="file_hasil_path">
                                    <input type="hidden" name="file_hasil_name" id="file_hasil_name">
                                    @error('file_hasil')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

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
<!-- Modal Konfirmasi -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="statusUpdateModalLabel"><i class="fas fa-info-circle me-2"></i>Status Permohonan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="statusUpdateModalBody">
        Status permohonan berhasil diperbarui!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
      </div>
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
            const buttonsContainer = document.getElementById('status-buttons-container');
            const selectedInfoContainer = document.getElementById('selected-status-info');
            const selectedInfoText = document.getElementById('selected-status-text');
            const cancelBtn = document.getElementById('cancel-selection-btn');
            const dynamicFormContainer = document.getElementById('dynamic-form-fields');
            const btnGenerate = document.getElementById('btn-generate-attach');
            const toggleLink = document.getElementById('toggle-upload-mode');
            const autoSection = document.getElementById('auto-generate-section');
            const manualSection = document.getElementById('manual-upload-section');
            const manualFileInput = manualSection.querySelector('[name="file_hasil"]');
            const autoGenerateInputPath = document.getElementById('file_hasil_path');
            const autoGenerateInputName = document.getElementById('file_hasil_name');

            const setUploadMode = (isManual) => {
                manualSection.classList.toggle('d-none', !isManual);
                autoSection.classList.toggle('d-none', isManual);

                if (isManual) {
                    manualFileInput.disabled = false; // Aktifkan input manual
                    autoGenerateInputPath.disabled = true; // NONAKTIFKAN input generate
                    autoGenerateInputName.disabled = true;
                    if (toggleLink) toggleLink.textContent = 'atau, generate file otomatis';
                } else {
                    manualFileInput.disabled = true; // NONAKTIFKAN input manual
                    autoGenerateInputPath.disabled = false; // Aktifkan input generate
                    autoGenerateInputName.disabled = false;
                    if (toggleLink) toggleLink.textContent = 'atau, upload file secara manual';
                }
            };

            if (toggleLink) {
                toggleLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isCurrentlyManual = !manualSection.classList.contains('d-none');
                    setUploadMode(!isCurrentlyManual);
                });
            }

            // --- Fungsi utama untuk mengatur tampilan ---
            const updateFormVisibility = (selectedStatus) => {
                const formSelesai = actionCard.querySelector('#formSelesai');
                const formDitolak = actionCard.querySelector('#formDitolak');
                const formEskalasi = actionCard.querySelector('#formEskalasi');
                const fileHasilInput = actionCard.querySelector('[name="file_hasil"]');
                const alasanPenolakanInput = actionCard.querySelector('[name="alasan_penolakan"]');
                const alasanEskalasiInput = actionCard.querySelector('[name="alasan_eskalasi"]');

                // Selalu reset semua form
                [formSelesai, formDitolak, formEskalasi].forEach(form => form && form.classList.add('d-none'));
                [fileHasilInput, alasanPenolakanInput, alasanEskalasiInput].forEach(input => input && input.removeAttribute('required'));

                if (selectedStatus) {
                    if (buttonsContainer) buttonsContainer.classList.add('d-none');
                    if (selectedInfoContainer) selectedInfoContainer.classList.remove('d-none');
                    if (selectedInfoText) selectedInfoText.textContent = selectedStatus.replace(/_/g, ' ');
                    if (dynamicFormContainer) dynamicFormContainer.classList.remove('d-none');

                    if (selectedStatus === 'selesai') {
                        if (formSelesai) formSelesai.classList.remove('d-none');
                        if (fileHasilInput) fileHasilInput.setAttribute('required', 'required');
                    } else if (selectedStatus === 'ditolak') {
                        if (formDitolak) formDitolak.classList.remove('d-none');
                        if (alasanPenolakanInput) alasanPenolakanInput.setAttribute('required', 'required');
                    } else if (selectedStatus === 'eskalasi') {
                        if (formEskalasi) formEskalasi.classList.remove('d-none');
                        if (alasanEskalasiInput) alasanEskalasiInput.setAttribute('required', 'required');
                    }
                } else {
                    if (buttonsContainer) buttonsContainer.classList.remove('d-none');
                    if (selectedInfoContainer) selectedInfoContainer.classList.add('d-none');
                    if (dynamicFormContainer) dynamicFormContainer.classList.add('d-none');
                }
            };

            // --- Event Listeners ---
            if (statusButtons) {
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
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    statusInput.value = '';
                    updateFormVisibility('');
                });
            }

            if (btnGenerate) {
                btnGenerate.addEventListener('click', function() {
                    const isJsonRequest = btnGenerate.getAttribute('data-is-json') === 'true';
                    const permohonanId = "{{ $permohonan->id }}";
                    const url = `/admin/permohonan/${permohonanId}/generate-attach`;
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

                    if (!isJsonRequest) {
                        btnGenerate.disabled = true;
                        btnGenerate.title = 'Tidak bisa generate otomatis untuk permohonan kustom';
                        btnGenerate.classList.add('opacity-50');
                    }

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('file_hasil_path').value = data.file_path;
                                document.getElementById('file_hasil_name').value = data.file_name;
                                const fileInfo = document.getElementById('file-generated-info');
                                fileInfo.innerHTML = `<i class="fas fa-check-circle me-1"></i> File <strong>${data.file_name}</strong> berhasil dilampirkan.`;
                                fileInfo.classList.remove('d-none');
                                this.style.display = 'none';
                            } else {
                                alert('Gagal membuat file: ' + (data.message || 'Coba lagi.'));
                                this.disabled = false;
                                this.innerHTML = '<i class="fas fa-cogs me-2"></i> Generate & Lampirkan File';
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan. Lihat console untuk detail.');
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-cogs me-2"></i> Generate & Lampirkan File';
                        });
                });
            }

            // --- Inisialisasi ---
            let initialStatus = "{{ $permohonan->status }}";
            if (initialStatus === 'sudah eskalasi') {
                statusInput.value = 'selesai';
                updateFormVisibility('selesai');
            } else {
                updateFormVisibility('');
            }
        
        }
    });
</script>
@endpush
