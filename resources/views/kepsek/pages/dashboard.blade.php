@extends('kepsek.layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Statistik Nilai Guru</h5>
                        <form action="{{ route('dashboard_kepsek') }}" method="get" class="mb-3">
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
                        <div id="chart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <a href="{{ route('formulir_pending') }}">
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

            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <a href="{{ route('formulir_selesai') }}">
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

            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ url('template/assetsnew/img/icons/unicons/detail-solid-240.png') }}"
                                    alt="Credit Card" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                    <a class="dropdown-item view-more" href="javascript:void(0);"
                                        data-target="#hiddenTable3">Tampilkan</a>
                                    <a class="dropdown-item delete-card" href="javascript:void(0);"
                                        data-target="#hiddenTable3">Tutup</a>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Kriteria Penilaian</span>
                        <h3 class="card-title text-nowrap mb-2">{{ $kriteria_pen }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <a href="{{ route('guru') }}">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ url('template/assetsnew/img/icons/unicons/user-detail-solid-240.png') }}"
                                        alt="Credit Card" class="rounded" />
                                </div>
                            </a>
                        </div>
                        <span class="fw-semibold d-block mb-1">Jumlah Guru</span>
                        <h3 class="card-title mb-2">{{ $akun_guru }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Tables -->
        <div class="row">
            <div class="col-lg-12 mb-4">
                <!-- Hidden Table 3 -->
                <div class="card">
                    <div id="hiddenTable3" class="table-responsive text-nowrap d-none">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kriteria</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriteria as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kriteria }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Hidden Table 4 -->
                <div class="card mt-4">
                    <div id="hiddenTable4" class="table-responsive text-nowrap d-none">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Guru</th>
                                    <th>Pangkat</th>
                                    <th>Jabatan</th>
                                    <th>Unit Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guru as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->pangkat }}</td>
                                        <td>{{ $item->jabatan }}</td>
                                        <td>{{ $item->unit_kerja }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.view-more').forEach(function(element) {
            element.addEventListener('click', function() {
                document.querySelectorAll('.table-responsive').forEach(function(table) {
                    table.classList.add('d-none');
                });
                var targetId = this.getAttribute('data-target');
                var targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.classList.remove('d-none');
                } else {
                    console.warn("Target element with ID " + targetId + " does not exist.");
                }
            });
        });

        document.querySelectorAll('.delete-card').forEach(function(element) {
            element.addEventListener('click', function() {
                var targetId = this.getAttribute('data-target');
                var targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.classList.add('d-none');
                } else {
                    console.warn("Target element with ID " + targetId + " does not exist.");
                }
            });
        });

        var options = {
            series: @json($chartData),
            chart: {
                height: 400,
                type: 'bar'
            },
            title: {
                text: 'Nilai Akhir Guru per Periode'
            },
            xaxis: {
                categories: @json($guru_names),
                title: {
                    text: 'Nama Guru'
                }
            },
            yaxis: {
                title: {
                    text: 'Nilai Akhir'
                },
                labels: {
                    formatter: function(value) {
                        return value.toFixed(2); // Format to 2 decimal places
                    }
                }
            },
            dataLabels: {
                enabled: false // Disable data labels inside the bars
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value.toFixed(2); // Format tooltip values to 2 decimal places
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endsection
