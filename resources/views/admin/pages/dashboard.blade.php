@extends('admin.layouts.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Card 1 -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ url('template/assetsnew/img/icons/unicons/time-solid-240.png') }}"
                                    alt="chart success" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt1">
                                    <a class="dropdown-item view-more" href="javascript:void(0);"
                                        data-target="#hiddenTable1">Tampilkan</a>
                                    <a class="dropdown-item delete-card" href="javascript:void(0);"
                                        data-target="#hiddenTable1">Tutup</a>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Formulir Pending</span>
                        <h3 class="card-title mb-2">{{ $penilaian_guru_pending }}</h3>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ url('template/assetsnew/img/icons/unicons/check-square-solid-240.png') }}"
                                    alt="Credit Card" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt2" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt2">
                                    <a class="dropdown-item view-more" href="javascript:void(0);"
                                        data-target="#hiddenTable2">Tampilkan</a>
                                    <a class="dropdown-item delete-card" href="javascript:void(0);"
                                        data-target="#hiddenTable2">Tutup</a>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Formulir Selesai</span>
                        <h3 class="card-title text-nowrap mb-2">{{ $penilaian_guru_selesai }}</h3>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card">
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

            <!-- Card 4 -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ url('template/assetsnew/img/icons/unicons/user-detail-solid-240.png') }}"
                                    alt="Credit Card" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                    <a class="dropdown-item view-more" href="javascript:void(0);"
                                        data-target="#hiddenTable4">Tampilkan</a>
                                    <a class="dropdown-item delete-card" href="javascript:void(0);"
                                        data-target="#hiddenTable4">Tutup</a>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Jumlah Guru</span>
                        <h3 class="card-title mb-2">{{ $akun_guru }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Table 1 -->
        <div class="card">
            <div id="hiddenTable1" class="table-responsive text-nowrap d-none">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Guru</th>
                            <th>Tahun</th>
                            <th>Periode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($formulir as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->user->nama }}</td>
                                <td>{{ $item->tahun_penilaian->tahun_penilaian }}</td>
                                <td>{{ $item->periode_penilaian->periode_penilaian }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Hidden Table 2 -->
        <div class="card">
            <div id="hiddenTable2" class="table-responsive text-nowrap d-none">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Guru</th>
                            <th>Tahun</th>
                            <th>Periode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($formulirsel as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->user->nama }}</td>
                                <td>{{ $item->tahun_penilaian->tahun_penilaian }}</td>
                                <td>{{ $item->periode_penilaian->periode_penilaian }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

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
        <div class="card">
            <div id="hiddenTable4" class="table-responsive text-nowrap d-none">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Guru</th>
                            <th>NIP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nama_guru as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->username }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.view-more').forEach(function(element) {
            element.addEventListener('click', function() {
                // Hide all tables
                document.querySelectorAll('.table-responsive').forEach(function(table) {
                    table.classList.add('d-none');
                });

                // Show the clicked table
                var targetId = this.getAttribute('data-target');
                var targetElement = document.querySelector(targetId);

                // Check if the target element exists before trying to show it
                if (targetElement) {
                    targetElement.classList.remove('d-none');
                } else {
                    console.warn("Target element with ID " + targetId + " does not exist.");
                }
            });
        });

        document.querySelectorAll('.delete-card').forEach(function(element) {
            element.addEventListener('click', function() {
                // Hide the specific table by adding 'd-none' class
                var targetId = this.getAttribute('data-target');
                var targetElement = document.querySelector(targetId);

                // Check if the target element exists before trying to hide it
                if (targetElement) {
                    targetElement.classList.add('d-none');
                } else {
                    console.warn("Target element with ID " + targetId + " does not exist.");
                }
            });
        });
    </script>
@endsection
