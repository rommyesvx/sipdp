@extends('layouts.newAppUser')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Kartu untuk Update Informasi Profil --}}
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Kartu untuk Update Password --}}
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                 <div class="card-body p-4 p-md-5">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Kartu untuk Hapus Akun --}}
            <div class="card shadow-sm border-0 rounded-4">
                 <div class="card-body p-4 p-md-5">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection