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
                <h5 class="mb-0">Riwayat Penilaian Selesai</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table" id="table-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Guru</th>
                            <th>Tahun</th>
                            <th>Periode</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($formulir as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->user->nama }}</td>
                                <td>{{ $item->tahun_penilaian->tahun_penilaian }}</td>
                                <td>{{ $item->periode_penilaian->periode_penilaian }}</td>
                                <td class="text-{{ $item->predikat_class }}">
                                    {{ $item->nilai_akhir }} ({{ $item->predikat }})
                                    <div>
                                        <span class="badge bg-{{ $item->predikat_class }}">
                                            {{ $item->rating_perilaku_kerja }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('detail_riwayat_penilaian_selesai', $item->id) }}">
                                        <i class='bx bxs-right-arrow-circle'></i>
                                    </a>
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
