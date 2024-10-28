@extends('guru.layouts.main')
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
                <h5 class="mb-0">Riwayat Penilaian</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table" id="table-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($penilaian as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->tahun_penilaian->tahun_penilaian }}</td>
                                <td>{{ $item->periode_penilaian->periode_penilaian }}</td>
                                <td>
                                    @if ($item->status == 'SELESAI')
                                        <div class="badge bg-label-success me-1 font-size-12">Selesai</div>
                                    @elseif ($item->status == 'PENDING')
                                        <div class="badge bg-label-warning me-1 font-size-12">Pending</div>
                                    @elseif ($item->status == 'DRAFT')
                                        <div class="badge bg-label-danger me-1 font-size-12">Draft</div>
                                    @endif
                                </td>
                                <td class="text-{{ $item->predikat_class }}">
                                    {{ $item->nilai_akhir }} ({{ $item->predikat }})
                                    <div>
                                        <span class="badge bg-{{ $item->predikat_class }}">
                                            {{ $item->rating_perilaku_kerja }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if ($item->status == 'DRAFT')
                                        <a class="btn btn-success btn-sm"
                                            href="{{ route('detail_riwayat_penilaian', $item->id) }}"><i
                                                class='bx bxs-right-arrow-circle'></i></a>
                                    @endif
                                    @if ($item->status == 'PENDING')
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('detail_riwayat_penilaian_unchange', $item->id) }}"><i
                                                class='bx bx-show'></i></a>
                                    @endif
                                    @if ($item->status == 'SELESAI')
                                        <a class="btn btn-primary btn-sm"
                                            href="{{ route('download_dokumen', $item->id) }}"><i
                                                class='bx bxs-printer'></i></a>
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('detail_riwayat_penilaian_unchange', $item->id) }}"><i
                                                class='bx bx-show'></i></a>
                                    @endif
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
