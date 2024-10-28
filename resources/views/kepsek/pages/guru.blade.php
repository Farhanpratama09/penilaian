@extends('kepsek.layouts.main')
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
        <div class="card p-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Guru</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table" id="table-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Guru</th>
                            <th>Pangkat</th>
                            <th>Jabatan</th>
                            <th>Unit Kerja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($guru as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->pangkat }}</td>
                                <td>{{ $item->jabatan }}</td>
                                <td>{{ $item->unit_kerja }}</td>
                                <td>
                                    <a class="btn btn-success btn-sm" href="{{ route('detail_guru', $item->id) }}"><i
                                            class='bx bxs-right-arrow-circle'></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table-1').DataTable();
        });
    </script>
@endsection
