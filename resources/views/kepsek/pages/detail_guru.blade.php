@extends('kepsek.layouts.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <a href="{{ route('guru') }}" class="btn btn-warning btn-sm me-3">
                        <i class='bx bxs-left-arrow-square'></i>
                    </a>
                    <h3 class="text-success mb-0">{{ $guru->nama }}</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Card 1 -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <a href="{{ route('formulir_pending', ['user_id' => $guru->id]) }}">
                            <div class="avatar flex-shrink-0 mx-auto mb-3">
                                <img src="{{ url('template/assetsnew/img/icons/unicons/time-solid-240.png') }}"
                                    alt="Formulir Pending" class="rounded" />
                            </div>
                        </a>
                        <span class="fw-semibold d-block mb-1">Penilaian Belum Diperiksa</span>
                        <h3 class="card-title mb-0">{{ $penilaian_guru_pending }}</h3>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <a href="{{ route('formulir_selesai', ['user_id' => $guru->id]) }}">
                            <div class="avatar flex-shrink-0 mx-auto mb-3">
                                <img src="{{ url('template/assetsnew/img/icons/unicons/check-square-solid-240.png') }}"
                                    alt="Formulir Selesai" class="rounded" />
                            </div>
                        </a>
                        <span class="fw-semibold d-block mb-1">Penilaian Sudah Diperiksa</span>
                        <h3 class="card-title mb-0">{{ $penilaian_guru_selesai }}</h3>
                    </div>
                </div>
            </div>

            <!-- Dropdown and Chart -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('detail_guru', $guru->id) }}" method="get" class="mb-3">
                            @csrf
                            <div class="d-flex align-items-center">
                                <label for="year" class="me-2">Tahun:</label>
                                <select name="year" id="year" class="form-control w-auto"
                                    onchange="this.form.submit()">
                                    @foreach ($tahun as $item)
                                        <option value="{{ $item->tahun_penilaian }}" @selected($tahun_sekarang->tahun_penilaian == $item->tahun_penilaian)>
                                            {{ $item->tahun_penilaian }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                        <div id="chart" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var options = {
            series: @json($data),
            chart: {
                height: 400,
                type: 'radar',
                dropShadow: {
                    enabled: true,
                    blur: 1,
                    left: 1,
                    top: 1
                }
            },
            title: {
                text: 'Hasil Penilaian Tahun {{ $tahun_sekarang->tahun_penilaian }}',
            },
            stroke: {
                width: 2
            },
            fill: {
                opacity: 0.1
            },
            markers: {
                size: 0
            },
            yaxis: {
                stepSize: 1
            },
            xaxis: {
                categories: @json($label)
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endsection
