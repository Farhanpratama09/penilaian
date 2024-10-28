@extends('admin.layouts.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @if (session()->get('errors'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="uil uil-info-circle me-2"></i>{{ session()->get('errors')->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="uil uil-info-circle me-2"></i>{{ session()->get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Periode</h5>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="bx bx-calendar-plus me-1"></i> Periode
                </button>
            </div>
            <div class="table-wrapper">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="table-1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($periode as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->periode_penilaian }}</td>
                                    <td>
                                        @if ($item->aktif == 1)
                                            <span class="badge bg-label-success me-1">Aktif</span>
                                        @else
                                            <span class="badge bg-label-danger me-1">Tidak Aktif</span>
                                        @endif
                                    <td>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop-{{ $item->id }}"><i
                                                class='bx bx-calendar-x me-1'></i></button>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#edit-{{ $item->id }}"><i
                                                class='bx bx-calendar-edit me-1'></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modal tambah periode --}}
    <form action="{{ route('tambah_periode') }}" method="post">
        @method('post')
        @csrf
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Periode Penilaian</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="periode_penilaian" class="col-sm-2 col-form-label">Periode</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="periode_penilaian" name="periode_penilaian">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="aktif" class="col-2 col-form-label">Status</label>
                            <div class="col-10">
                                <select class="form-select" name="aktif" id="aktif">
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- end modal --}}

    @foreach ($periode as $item)
        {{-- modal hapus akun periode --}}
        <form action="{{ route('delete_periode', $item->id) }}" method="post">
            @method('delete')
            @csrf
            <div class="modal fade" id="staticBackdrop-{{ $item->id }}" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Hapus Periode Penilaian</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah anda yakin ingin menghapus periode penilaian ini?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        {{-- end modal --}}

        {{-- modal edit periode --}}
        <form action="{{ route('edit_periode', $item->id) }}" method="post">
            @method('put')
            @csrf
            <div class="modal fade" id="edit-{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Periode Penilaian</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="periode_penilaian" class="col-sm-2 col-form-label">Periode</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                        id="periode_penilaian-{{ $item->id }}" name="periode_penilaian"
                                        value="{{ $item->periode_penilaian }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="aktif" class="col-2 col-form-label">Status</label>
                                <div class="col-10">
                                    <select class="form-select" name="aktif" id="aktif-{{ $item->id }}">
                                        <option value="1" {{ $item->aktif == 1 ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ $item->aktif == 0 ? 'selected' : '' }}>Tidak Aktif
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        {{-- end modal --}}
    @endforeach
@endsection
