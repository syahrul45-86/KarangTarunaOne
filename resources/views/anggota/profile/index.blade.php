@extends('anggota.layouts.master')

@section('content')
<div class="page-body">
    <div class="container-xl">
        {{-- Profile View --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Profile</h3>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <p class="form-control-plaintext">{{ $anggota->name }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <p class="form-control-plaintext">{{ $anggota->email }}</p>
                    </div>
                </div>

                {{-- Tampilkan image --}}
                <div class="mb-3">
                    <label class="form-label">Profile Image</label><br>
                    @if($anggota->image)
                        <img src="{{ asset('storage/'.$anggota->image) }}" alt="Profile" width="120" class="img-thumbnail mb-2">
                    @else
                        <p class="text-muted">Belum ada foto</p>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <p class="form-control-plaintext">{{ $anggota->role }}</p>
                </div>

                <div class="mb-3">
                    <a href="{{ route('anggota.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                </div>

            </div>
        </div>
    </div>
</div>
@include('anggota.layouts.footer')
@endsection
