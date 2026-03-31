@extends('sekretaris.layouts.master')

@section('title', 'Uji Upload QR Code')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    Uji Upload QR Code Absensi
                </div>

                <div class="card-body">

                    <p class="text-muted">
                        Upload gambar QR Code (PNG / JPG).
                        QR akan diproses dan divalidasi langsung oleh server.
                    </p>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('sekretaris.absensi.upload-qr', $form->id) }}"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload QR Code</label>
                            <input type="file" name="qr" accept=".svg" required>
                        </div>

                        <button class="btn btn-success w-100">
                            Upload & Validasi QR
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
