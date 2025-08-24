@extends('layouts.kepala')

@section('title', 'Detail Permohonan Eskalasi #' . $permohonan->id)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    /* Style untuk tombol status kustom yang interaktif */
    .btn-decision {
        text-align: left;
        padding: 1rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 0.75rem;
        border: 2px solid transparent;
        transition: all 0.2s ease-in-out;
    }

    .btn-decision .icon-circle {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-right: 1rem;
        color: #fff;
    }

    .btn-decision.btn-approve {
        background-color: #e0f8f0;
        color: #146c43;
    }

    .btn-decision.btn-approve .icon-circle {
        background-color: #198754;
    }

    .btn-decision.btn-approve:hover {
        border-color: #198754;
    }

    .btn-decision.btn-reject {
        background-color: #fdeeee;
        color: #b02a37;
    }

    .btn-decision.btn-reject .icon-circle {
        background-color: #dc3545;
    }

    .btn-decision.btn-reject:hover {
        border-color: #dc3545;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0 text-gray-800">Detail Permohonan Eskalasi</h1>
        <a href="{{ route('kepala.permohonan.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">

            <!-- Card: Informasi Permohonan (Admin-style) -->
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
                                <div class="text-muted small">{{ $permohonan->asal }}</div>
                            </div>
                        </div>
                        <div class="col-md-8 py-4">
                            <div class="row mb-2">
                                <div class="col-6 mb-2">
                                    <span class="fw-semibold text-muted small">Tanggal Diajukan</span><br>
                                    <span>{{ $permohonan->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="col-6 mb-2">
                                    <span class="fw-semibold text-muted small">Tipe File</span><br>
                                    <span class="text-uppercase">{{ $permohonan->tipe }}</span>
                                </div>
                                <div class="col-12 mb-2">
                                    <span class="fw-semibold text-muted small">Tujuan Permohonan</span><br>
                                    <span>{{ $permohonan->tujuan }}</span>
                                    @if ($permohonan->tujuan == 'Lainnya' && $permohonan->tujuan_lainnya_text)
                                        <br><span class="fst-italic text-muted">{{ $permohonan->tujuan_lainnya_text }}</span>
                                    @endif
                                </div>
                                <div class="col-12 mb-2">
                                    <span class="fw-semibold text-muted small">Jenis Data Diminta</span><br>
                                    {!! $permohonan->formatted_jenis_data !!}
                                </div>
                                @if(!empty($permohonan->kolom_diminta) && \Illuminate\Support\Str::isJson($permohonan->kolom_diminta))
                                <div class="col-12 mb-2">
                                    <span class="fw-semibold text-muted small">Kolom Data Diminta</span><br>
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
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($kolomList as $kolom)
                                        <span class="badge text-dark-emphasis bg-light-subtle border fw-medium">
                                            {{ $columnLabels[$kolom] ?? ucfirst(str_replace('_', ' ', $kolom)) }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endif
                                @if ($permohonan->catatan)
                                <div class="col-12 mb-2">
                                    <span class="fw-semibold text-muted small">Catatan Tambahan</span><br>
                                    <span>{{ $permohonan->catatan }}</span>
                                </div>
                                @endif
                                @if ($permohonan->file_permohonan)
                                <div class="col-12 mb-2">
                                    <span class="fw-semibold text-muted small">Surat Pengantar</span><br>
                                    <a href="{{ route('kepala.permohonan.downloadSurat', ['id' => $permohonan->suratPengantar->id]) }}" target="_blank" class="text-decoration-none">
                                        <i class="fas fa-file-pdf text-danger"></i>
                                        {{ $permohonan->suratPengantar->nama_asli_file }}
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kartu Preview Data --}}
            <div class="card shadow-sm border-0 rounded-4 detail-card">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-primary">Preview Data yang Diminta</h5>
                </div>
                <div class="card-body">
                    {{-- Cek apakah ada data yang bisa ditampilkan --}}
                    @if ($pegawaisPreview->isNotEmpty() && !empty($kolomTampil))
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        {{-- 1. Membuat Header Tabel secara Dinamis --}}
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
                            <p class="mb-1">Preview data tidak dapat ditampilkan.</p>
                            <small>Ini bisa terjadi jika permohonan diajukan dengan kriteria khusus atau tidak ada data yang cocok.</small>
                        </div>
                    @endif
                </div>
            </div>
            <hr>
        </div>
        <div class="col-lg-4">
            <form method="POST" action="{{ route('kepala.permohonan.update', $permohonan->id) }}">
                @csrf
                @method('POST')
                <div class="card shadow-sm border-0 rounded-4 position-sticky top-0 action-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-edit fs-4 me-3 text-primary"></i>
                            <h5 class="m-0 fw-bold">Ubah Status Permohonan</h5>
                        </div>

                        @if($permohonan->alasan_eskalasi)
                        <div class="alert alert-info small mb-3"><strong class="d-block">Catatan dari Admin:</strong>"{{ $permohonan->alasan_eskalasi }}"</div>
                        @endif

                        <input type="hidden" name="keputusan" id="keputusanInput" value="">

                        <p class="small text-muted">Pilih salah satu tindakan di bawah ini.</p>
                        <div id="decision-buttons-container" class="d-grid gap-3 mb-3">
                            <button type="button" class="btn btn-decision btn-approve" data-keputusan="setujui">
                                <span class="icon-circle"><i class="fas fa-check"></i></span> Setujui Permohonan
                            </button>
                            <button type="button" class="btn btn-decision btn-reject" data-keputusan="tolak">
                                <span class="icon-circle"><i class="fas fa-times"></i></span> Tolak Permohonan
                            </button>
                        </div>

                        <div id="catatan-form-section" class="d-none">
                            <hr>
                            <div class="mb-3">
                                <label for="catatan_kepala_bidang" class="form-label fw-semibold" id="catatanLabel">Catatan</label>
                                <textarea name="catatan_kepala_bidang" class="form-control" rows="4" placeholder="Tulis catatan persetujuan atau alasan penolakan..."></textarea>
                                @error('catatan_kepala_bidang')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" id="cancel-decision-btn" class="btn btn-sm btn-secondary">Batal</button>
                                <button type="submit" class="btn btn-primary rounded-pill fw-bold">Kirim Keputusan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const decisionButtons = document.querySelectorAll('.btn-decision');
        const keputusanInput = document.getElementById('keputusanInput');
        const catatanFormSection = document.getElementById('catatan-form-section');
        const buttonsContainer = document.getElementById('decision-buttons-container');
        const cancelBtn = document.getElementById('cancel-decision-btn');
        const catatanTextarea = document.querySelector('[name="catatan_kepala_bidang"]');
        const catatanLabel = document.getElementById('catatanLabel');

        const toggleFormVisibility = (decision) => {
            if (decision) {
                buttonsContainer.classList.add('d-none');
                catatanFormSection.classList.remove('d-none');

                // Ubah label dan requirement textarea sesuai keputusan
                if (decision === 'tolak') {
                    catatanLabel.innerHTML = 'Alasan Penolakan <span class="text-danger">*</span>';
                    catatanTextarea.setAttribute('required', 'required');
                } else { // jika 'setujui'
                    catatanLabel.innerHTML = 'Catatan Persetujuan (Opsional)';
                    catatanTextarea.removeAttribute('required');
                }
            } else {
                buttonsContainer.classList.remove('d-none');
                catatanFormSection.classList.add('d-none');
                catatanTextarea.removeAttribute('required');
            }
        };

        decisionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const newDecision = this.dataset.keputusan;
                keputusanInput.value = newDecision;
                toggleFormVisibility(newDecision);
            });
        });

        cancelBtn.addEventListener('click', function() {
            keputusanInput.value = '';
            toggleFormVisibility('');
        });
    });
</script>
@endpush