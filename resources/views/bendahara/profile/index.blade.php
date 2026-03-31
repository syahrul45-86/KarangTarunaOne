@extends('bendahara.layouts.master')

@section('content')
<div class="page-body">
    <div class="container-xl">
        {{-- Profile Update --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Profile </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('bendahara.profile.index') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $bendahara->name) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" value="{{ old('email', $bendahara->email) }}">
                        </div>
                    </div>

                    {{-- Tambah image --}}
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label><br>
                        @if($bendahara->image)
                            <img src="{{ asset('storage/'.$bendahara->image) }}" alt="Profile" width="120" class="img-thumbnail mb-2">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" name="role" value="{{ old('role', $bendahara->role) }}" readonly>
                        {{-- kalau tidak boleh diedit, pakai readonly --}}
                    </div>

                    <div class="mb-3">
                        <a href="{{ route('bendahara.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                    </div>

                </form>
            </div>
        </div>


    </div>
</div>
@endsection
