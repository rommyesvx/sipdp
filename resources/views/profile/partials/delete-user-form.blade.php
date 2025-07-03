<section>
    <header>
        <h2 class="h4 fw-bold text-dark">
            Hapus Akun
        </h2>
        <p class="mt-1 text-muted">
            Setelah akun Anda dihapus, semua data akan dihapus secara permanen.
        </p>
    </header>

    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">
        Hapus Akun
    </button>

    <div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}" class="p-4">
                    @csrf
                    @method('delete')

                    <h5 class="modal-title mb-3 fw-bold">
                        Apakah Anda yakin ingin menghapus akun Anda?
                    </h5>
                    <p class="text-muted">
                        Sekali lagi, semua data Anda akan dihapus permanen. Mohon masukkan password Anda untuk mengonfirmasi penghapusan akun.
                    </p>

                    <div class="mt-3">
                        <label for="password_delete" class="form-label visually-hidden">Password</label>
                        <input id="password_delete" name="password" type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Password">
                        @error('password', 'userDeletion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>