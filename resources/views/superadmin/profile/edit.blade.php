@extends('superadmin.layouts.master')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- Profile Edit --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Profile <a href="{{ route('superadmin.profile.index') }}" class="btn btn-secondary justify-content-lg-center">Cancel</a></h3>

            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- jangan pakai @method('PUT') --}}


                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   name="name"
                                   value="{{ old('name', $superadmin->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email', $superadmin->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" name="role" value="{{ $superadmin->role }}" readonly>
                    </div>

                    {{-- Image --}}
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file"
                               class="form-control @error('image') is-invalid @enderror"
                               name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if($superadmin->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$superadmin->image) }}" alt="Profile Image" width="100" class="rounded">
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit">Update Profile</button>

                    </div>

                        {{-- Password Update --}}
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Password Update</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.profile.password') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control" name="current_password">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control" name="password">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>

                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@include('superadmin.layouts.footer')
@endsection
