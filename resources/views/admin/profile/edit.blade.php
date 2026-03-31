@extends('admin.layouts.master')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- Profile Edit --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Edit Profile
                    <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary">Cancel</a>
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   name="name"
                                   value="{{ old('name', $admin->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email', $admin->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" name="role" value="{{ $admin->role }}" readonly>
                    </div>

                  <div class="mb-3">
                    <label class="form-label">RT / RW</label>
                    <select name="rt_id" class="form-control @error('rt_id') is-invalid @enderror">
                        <option value="">-- Pilih RT (opsional) --</option>
                        @foreach($rts as $rt)
                            <option value="{{ $rt->id }}" {{ (old('rt_id', $admin->rt_id) == $rt->id) ? 'selected' : '' }}>
                                RT {{ $rt->nama_rt }} / RW {{ $rt->rw }}
                            </option>
                        @endforeach
                    </select>
                    @error('rt_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

                        @if($admin->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$admin->image) }}" alt="Profile Image" width="100" class="rounded">
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Password Update --}}
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Password Update</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.password') }}" method="POST">
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

    </div>
</div>
@include('admin.layouts.footer')
@endsection
