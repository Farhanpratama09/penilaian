@extends('guru.layouts.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Card 1 -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <a href="{{ route('riwayat_penilaian', ['status' => 'pending']) }}">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ url('template/assetsnew/img/icons/unicons/time-solid-240.png') }}"
                                        alt="chart success" class="rounded" />
                                </div>
                            </a>
                        </div>
                        <span class="fw-semibold d-block mb-1">Penilaian Belum Diperiksa</span>
                        <h3 class="card-title mb-2">{{ $penilaian_guru_pending }}</h3>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <a href="{{ route('riwayat_penilaian', ['status' => 'selesai']) }}">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ url('template/assetsnew/img/icons/unicons/check-square-solid-240.png') }}"
                                        alt="Credit Card" class="rounded" />
                                </div>
                            </a>
                        </div>
                        <span class="fw-semibold d-block mb-1">Penilaian Sudah Diperiksa</span>
                        <h3 class="card-title text-nowrap mb-2">{{ $penilaian_guru_selesai }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('dashboard_guru') }}" method="get">
                            @csrf
                            <select name="year" id="year" class="form-control w-auto" onchange="this.form.submit()">
                                @foreach ($tahun as $item)
                                    <option value="{{ $item->tahun_penilaian }}" @selected($tahun_sekarang->tahun_penilaian == $item->tahun_penilaian)>
                                        {{ $item->tahun_penilaian }}</option>
                                @endforeach
                            </select>
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
