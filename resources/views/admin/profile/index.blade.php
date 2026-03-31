@extends('admin.layouts.master')

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
                        <p class="form-control-plaintext">{{ $admin->name }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <p class="form-control-plaintext">{{ $admin->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">rt/rw</label>
                        <p class="form-control-plaintext"> @if($admin->rt)
                                {{ $admin->rt->nama_rt }} / {{ $admin->rt->rw }}
                            @else
                                -
                            @endif</p>
                    </div>

                </div>

                {{-- Tampilkan image --}}
                <div class="mb-3">
                    <label class="form-label">Profile Image</label><br>
                    @if($admin->image)
                        <img src="{{ asset('storage/'.$admin->image) }}" alt="Profile" width="120" class="img-thumbnail mb-2">
                    @else
                        <p class="text-muted">Belum ada foto</p>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <p class="form-control-plaintext">{{ $admin->role }}</p>
                </div>

                <div class="mb-3">
                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                </div>

            </div>
        </div>
    </div>
</div>
@include('admin.layouts.footer')
@endsection
