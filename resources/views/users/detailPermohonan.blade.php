@extends('layouts.newAppUser')

@section('title', 'Detail Permohonan #' . $permohonan->id)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    .request-header-gradient {
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    }

    .timeline {
        position: relative;
    }

    /* Garis Vertikal Timeline */
    .timeline::before {
        content: '';
        position: absolute;
        left: 12px;
        /* Posisi garis di tengah dot */
        top: 5px;
        bottom: 5px;
        width: 3px;
        background-color: #e9ecef;
        border-radius: 3px;
    }

    .timeline-step {
        position: relative;
        padding-left: 32px;
        /* Jarak untuk dot dan spasi */
        margin-bottom: 2rem;
    }

    .timeline-step:last-child {
        margin-bottom: 0;
    }

    /* Titik (Dot) pada Timeline */
    .timeline-dot {
        position: absolute;
        left: 0;
        top: 5px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: var(--bs-body-bg);
        border: 3px solid #ced4da;
        /* Warna dot abu-abu (belum tercapai) */
        transition: all 0.3s ease;
    }

    /* Style dot untuk status yang sudah lewat (completed) */
    .timeline-step.completed .timeline-dot {
        border-color: #6759ff;
        /* Warna ungu */
        background-color: #e7e7ff;
    }

    /* Style dot untuk status saat ini (active) */
    .timeline-step.active .timeline-dot {
        border-color: var(--bs-success);
        background-color: var(--bs-success);
    }

    /* Style dot untuk status ditolak */
    .timeline-step.rejected .timeline-dot {
        border-color: var(--bs-danger);
        background-color: var(--bs-danger);
    }

    /* Kartu konten di setiap langkah timeline */
    .timeline-content {
        background-color: #fff;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .05);
    }

    .star-rating i {
        cursor: pointer;
        transition: color 0.2s ease;
    }
</style>
@endpush

@section('content')
<div class="bg-body-secondary">
    <div class="container py-5">
        <a href="{{ route('users.riwayat') }}" class="btn btn-light border shadow-sm mb-4">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Riwayat
        </a>

        <div class="card shadow-sm rounded-4 border-0 mb-4">
            <div class="card-header p-4 d-flex flex-wrap justify-content-between align-items-center gap-3 border-bottom-0 request-header-gradient">
                <div>
                    <h2 class="h4 fw-bold mb-0">Detail Permohonan</h2>
                    <p class="text-muted small mb-0 mt-1">ID: {{ $permohonan->nomor_permohonan }}</p>
                </div>

                @php
                $statusConfig = ['class' => 'bg-secondary-subtle text-secondary-emphasis', 'icon' => 'fa-hourglass-half', 'text' => 'Menunggu'];
                if ($permohonan->status == 'selesai') { $statusConfig = ['class' => 'bg-success-subtle text-success-emphasis', 'icon' => 'fa-check-circle', 'text' => 'Disetujui']; }
                elseif ($permohonan->status == 'ditolak') { $statusConfig = ['class' => 'bg-danger-subtle text-danger-emphasis', 'icon' => 'fa-times-circle', 'text' => 'Ditolak']; }
                elseif ($permohonan->status == 'diproses') { $statusConfig = ['class' => 'bg-warning-subtle text-warning-emphasis', 'icon' => 'fa-sync-alt fa-spin', 'text' => 'Diproses']; }
                elseif ($permohonan->status == 'eskalasi') { $statusConfig = ['class' => 'badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-eskalasi', 'icon' => 'fas fa-arrow-up', 'text' => 'Eskalasi']; }
                @endphp
                <div class="badge rounded-pill fs-6 fw-semibold d-inline-flex align-items-center gap-2 px-3 py-2 {{ $statusConfig['class'] }}">
                    <i class="fas {{ $statusConfig['icon'] }}"></i>
                    <span>{{ $statusConfig['text'] }}</span>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <div class="row g-5">
                    <div class="col-md-6">
                        <div class="mb-4"><label class="form-label text-muted small text-uppercase fw-medium">Tujuan Permohonan</label>
                            <p class="fs-5 mb-0">{{ $permohonan->tujuan }}</p>
                        </div>
                        <div class="mb-4"><label class="form-label text-muted small text-uppercase fw-medium">Jenis Data yang Diminta</label>
                            {!! $permohonan->formatted_jenis_data !!}
                        </div>
                        @if(!empty($permohonan->kolom_diminta))
                        <div class="mb-4">
                            <label class="form-label text-muted small text-uppercase fw-medium">Kolom Data yang Diminta</label>
                            <div>
                                @php
                                $kolomList = json_decode($permohonan->kolom_diminta, true);

                                // "Kamus" untuk menerjemahkan nama kolom menjadi label yang ramah
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
                        @if ($permohonan->catatan)<div class="mb-4"><label class="form-label text-muted small text-uppercase fw-medium">Catatan Tambahan</label>
                            <div class="bg-body-tertiary border rounded-3 p-3">{{ $permohonan->catatan }}</div>
                        </div>@endif
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4"><label class="form-label text-muted small text-uppercase fw-medium">Tipe File yang Diminta</label>
                            <p class="fs-5 text-uppercase mb-0">{{ $permohonan->tipe }}</p>
                        </div>
                        <div class="mb-4"><label class="form-label text-muted small text-uppercase fw-medium">Tanggal Diajukan</label>
                            <p class="fs-5 mb-0">{{ $permohonan->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                        @if ($permohonan->file_permohonan)
                        <div class="mb-4">
                            <label class="form-label text-muted small text-uppercase fw-medium">Surat Pengantar</label>
                            <a href="{{ route('permohonan.downloadHasil', ['id' => $permohonan->id]) }}" target="_blank" class="text-decoration-none">
                                <div class="border border-2 border-dashed rounded-3 p-3 d-flex align-items-center gap-3">
                                    <div class="fs-2 text-danger"><i class="fas fa-file-pdf"></i></div>
                                    <div>
                                        <p class="fw-semibold mb-0">Surat Pengantar.pdf</p>
                                        <small class="text-primary fw-medium">Lihat File di Tab Baru</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">Status Permohonan</h4>
                    <button class="btn btn-outline-secondary btn-sm rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#timelineCollapse" aria-expanded="false" aria-controls="timelineCollapse">
                        Tampilkan Riwayat Lengkap
                    </button>
                </div>

                {{-- Menampilkan notifikasi status terakhir --}}
                @if ($permohonan->status == 'selesai')
                <div class="alert alert-success border-0 border-start border-5 border-success">
                    <div class="d-flex align-items-center"><i class="fas fa-check-circle fs-4 me-3"></i>
                        <div>
                            <p class="fw-bold mb-0">Permohonan Anda telah disetujui</p>
                            <p class="mb-0 small">Data telah dikirim dan dapat diunduh pada bagian di bawah.</p>
                        </div>
                    </div>
                </div>
                @elseif ($permohonan->status == 'ditolak')
                <div class="alert alert-danger border-0 border-start border-5 border-danger">
                    <div class="d-flex align-items-center"><i class="fas fa-times-circle fs-4 me-3"></i>
                        <div>
                            <p class="fw-bold mb-0">Permohonan Anda ditolak</p>
                            <p class="mb-0 small">Silakan lihat alasan penolakan pada bagian di bawah.</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="alert alert-warning border-0 border-start border-5 border-warning">
                    <div class="d-flex align-items-center"><i class="fas fa-sync-alt fa-spin fs-4 me-3"></i>
                        <div>
                            <p class="fw-bold mb-0">Permohonan Anda sedang diproses</p>
                            <p class="mb-0 small">Kami akan memberi notifikasi jika ada pembaruan status.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="collapse" id="timelineCollapse">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="timeline">
                        @php
                        $all_steps = ['menunggu', 'diproses', 'eskalasi', 'selesai', 'ditolak'];
                        $current_status_index = array_search($permohonan->status, $all_steps);
                        $step_config = [
                        'menunggu' => ['title' => 'Diterima', 'desc' => 'Permohonan Anda telah kami terima dan akan segera diverifikasi oleh tim.', 'date' => $permohonan->created_at],
                        'diproses' => ['title' => 'Diproses', 'desc' => 'Permohonan Anda sedang dalam peninjauan dan proses pengerjaan.', 'date' => $permohonan->updated_at],
                        'eskalasi' => ['title' => 'Eskalasi', 'desc' => 'Permohonan memerlukan peninjauan lebih lanjut oleh tim eskalasi.', 'date' => $permohonan->updated_at],
                        'selesai' => ['title' => 'Disetujui', 'desc' => 'Permohonan Anda telah disetujui.', 'date' => $permohonan->updated_at],
                        'ditolak' => ['title' => 'Ditolak', 'desc' => 'Permohonan Anda tidak dapat disetujui.', 'date' => $permohonan->updated_at],
                        ];
                        @endphp

                        {{-- Looping melalui setiap langkah yang mungkin --}}
                        @foreach(['menunggu', 'diproses', 'eskalasi'] as $step)
                        @php
                        $step_index = array_search($step, $all_steps);
                        if ($current_status_index >= $step_index) {
                        $status_class = $current_status_index > $step_index ? 'completed' : ($step == 'eskalasi' ? 'escalated' : 'active');
                        if(in_array($permohonan->status, ['selesai', 'ditolak'])) $status_class = 'completed';
                        if($permohonan->status == 'eskalasi' && $step == 'diproses') $status_class = 'completed';
                        if($permohonan->status == 'diproses') $status_class = 'active';
                        }
                        @endphp
                        @if($current_status_index >= $step_index)
                        <div class="timeline-step {{ $status_class }}">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content card card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0">{{ $step_config[$step]['title'] }}</h6><small class="text-muted">{{ $step_config[$step]['date']->format('d M Y, H:i') }}</small>
                                </div>
                                <p class="text-muted mb-0 mt-1">{{ $step_config[$step]['desc'] }}</p>
                            </div>
                        </div>
                        @endif
                        @endforeach

                        {{-- Langkah Terakhir: Disetujui atau Ditolak --}}
                        @if(in_array($permohonan->status, ['selesai', 'ditolak']))
                        <div class="timeline-step {{ $permohonan->status == 'selesai' ? 'active' : 'rejected' }}">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content card card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0">{{ $step_config[$permohonan->status]['title'] }}</h6><small class="text-muted">{{ $step_config[$permohonan->status]['date']->format('d M Y, H:i') }}</small>
                                </div>
                                <p class="text-muted mb-0 mt-1">{{ $step_config[$permohonan->status]['desc'] }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- KARTU HASIL DATA & FEEDBACK (JIKA SELESAI) --}}
        @if ($permohonan->status == 'selesai' && $permohonan->file_hasil)
        <div class="card rounded-4 border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h4 class="fw-bold">Unduh Data</h4>
                <p class="text-muted">Data yang Anda mohonkan telah tersedia. Silakan unduh file berikut.</p>
                <div class="border border-2 border-dashed rounded-3 p-3 d-flex align-items-center gap-3 mb-3">
                    <div class="fs-2 text-success"><i class="fas fa-file-excel"></i></div>
                    <div class="flex-grow-1">
                        <p class="fw-semibold mb-0">{{ basename($permohonan->file_hasil) }}</p><small class="text-muted">Disetujui pada: {{ $permohonan->updated_at->format('d M Y') }}</small>
                    </div>
                </div>
                {{-- Tombol Unduh dan Beri Feedback --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('permohonan.downloadHasil', ['id' => $permohonan->id]) }}" class="btn btn-primary fw-semibold">
                        <i class="fas fa-download me-2"></i>Unduh Hasil Data
                    </a>
                    @if (!$permohonan->feedback)
                    <a href="#feedbackCard" class="btn btn-outline-warning fw-semibold">
                        <i class="fas fa-star me-2"></i>Berikan Feedback
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- KARTU ALASAN PENOLAKAN (JIKA DITOLAK) --}}
        @if($permohonan->status === 'ditolak' && $permohonan->alasan_penolakan)
        <div class="card rounded-4 border-danger border-2 shadow-sm">
            <div class="card-body p-4">
                <h4 class="text-danger fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Alasan Penolakan</h4>
                <p class="fs-5 mt-3">{{ $permohonan->alasan_penolakan }}</p>
            </div>
        </div>
        @endif

        @if ($permohonan->status == 'selesai')
        <div class="card rounded-4 border-0 shadow-sm">
            <div class="card-body p-4">
                @if ($permohonan->feedback)
                <h5 class="fw-bold mb-3">Feedback Anda</h5>
                <p class="mb-2"><strong>Rating:</strong> <span class="text-warning">@for ($i = 0; $i < $permohonan->feedback->rating; $i++)<i class="fas fa-star"></i>@endfor</span><span class="text-body-tertiary">@for($i = $permohonan->feedback->rating; $i < 5; $i++)<i class="fas fa-star"></i>@endfor</span> ({{ $permohonan->feedback->rating }}/5)</p>
                <p class="mb-0"><strong>Pesan:</strong> "{{ $permohonan->feedback->pesan }}"</p>
                @else
                <h5 class="fw-bold mb-3" id="feedbackCard">Beri Feedback</h5>
                <form action="{{ route('feedback.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="permohonan_id" value="{{ $permohonan->id }}">
                    <div class="mb-3"><label class="form-label">Rating Kepuasan</label>
                        <div class="star-rating fs-2 text-body-tertiary"><i class="far fa-star" data-value="1"></i><i class="far fa-star" data-value="2"></i><i class="far fa-star" data-value="3"></i><i class="far fa-star" data-value="4"></i><i class="far fa-star" data-value="5"></i></div><input type="hidden" name="rating" id="ratingValue" value="" required>@error('rating')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3"><label for="pesan" class="form-label">Pesan (Opsional)</label><textarea class="form-control" name="pesan" rows="3"></textarea></div>
                    <button type="submit" class="btn btn-success">Kirim Feedback</button>
                </form>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
{{-- Script untuk rating bintang --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const starWrapper = document.querySelector('.star-rating');
        if (starWrapper) {
            const stars = starWrapper.querySelectorAll('i');
            const ratingInput = document.getElementById('ratingValue');
            let currentRating = 0;
            const updateStars = (rating) => {
                stars.forEach(star => {
                    if (star.dataset.value <= rating) {
                        star.classList.add('text-warning', 'fas');
                        star.classList.remove('text-body-tertiary', 'far');
                    } else {
                        star.classList.remove('text-warning', 'fas');
                        star.classList.add('text-body-tertiary', 'far');
                    }
                });
            };
            stars.forEach(star => {
                star.addEventListener('mouseover', () => {
                    const hoverValue = star.dataset.value;
                    stars.forEach(s => {
                        if (s.dataset.value <= hoverValue) {
                            s.classList.add('text-warning', 'fas');
                            s.classList.remove('text-body-tertiary', 'far');
                        } else {
                            s.classList.remove('text-warning', 'fas');
                            s.classList.add('text-body-tertiary', 'far');
                        }
                    });
                });
            });
            starWrapper.addEventListener('mouseout', () => {
                updateStars(currentRating);
            });
            stars.forEach(star => {
                star.addEventListener('click', () => {
                    currentRating = star.dataset.value;
                    ratingInput.value = currentRating;
                    updateStars(currentRating);
                });
            });
        }
    });
</script>
@endpush