@extends('guru.layouts.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @if (session()->has('errors'))
            <div class="alert alert-danger alert-dismissible fade show" style="width: 300px" role="alert">
                <i class="uil-exclamation-triangle font-size-16 text-danger me-2"></i>
                <strong>{{ session('errors')->first() }}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" style="width: 300px" role="alert">
                <i class="uil-exclamation-triangle font-size-16 text-success me-2"></i>
                <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" style="width: 300px" role="alert">
                <i class="uil-exclamation-triangle font-size-16 text-danger me-2"></i>
                <strong>{{ session('error') }}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Formulir <small>(Silahkan isi formulir penilaian berikut!)</small> </h5>

            </div>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('formulir_tahun') }}">
                @method('post')
                @csrf
                <div class="row mb-4">
                    <label for="horizontal-password-input" class="col-sm-3 col-form-label">Tahun</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="tahun" name="tahun">
                            @foreach ($tahun as $item)
                                <option value="{{ $item->id }}">{{ $item->tahun_penilaian }}</option>
                            @endforeach
                        </select>
                    </div>
                </div><!-- end row -->
                <div class="row mb-4">
                    <label for="horizontal-password-input" class="col-sm-3 col-form-label">Periode</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="periode" name="periode">
                            @foreach ($periode as $item)
                                <option value="{{ $item->id }}">{{ $item->periode_penilaian }}</option>
                            @endforeach
                        </select>
                    </div>
                </div><!-- end row -->
                <div class="row justify-content-end">
                    <div class="col-sm-9">
                        <div>
                            <button type="submit" class="btn btn-primary w-md">Next</button>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->
            </form><!-- end form -->
        </div>
    </div><!-- end card header -->
@endsection
