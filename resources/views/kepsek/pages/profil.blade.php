@extends('kepsek.layouts.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    @if (session()->has('password_error'))
                        <div class="alert alert-danger alert-dismissible fade show" style="width: 300px" role="alert">
                            <i class="uil-exclamation-triangle font-size-16 text-danger me-2"></i>
                            <strong>{{ session('password_error') }}</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- end page title -->

        <form id="myForm" method="POST" action="{{ route('edit_profil_kepsek') }}" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Profil</h5>

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

                    <div class="card border shadow-none mb-5">
                        <div class="card-header d-flex align-items-center">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                    <i class="uil uil-user"></i>
                                </div>
                            </div>
                            <h5 class="card-title mb-0">Data Profil</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="nama">Nama</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            value="{{ auth()->user()->nama }}" placeholder="Masukkan Nama">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="username">Username</label>
                                        <input type="text" class="form-control" id="username"
                                            value="{{ auth()->user()->username }}" name="username"
                                            placeholder="Masukkan Username">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="pangkat">Pangkat</label>
                                        <input type="text" class="form-control" id="pangkat" name="pangkat"
                                            value="{{ auth()->user()->pangkat }}" placeholder="Masukkan Pangkat">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="jabatan">Jabatan</label>
                                        <input type="text" class="form-control" id="jabatan"
                                            value="{{ auth()->user()->jabatan }}" name="jabatan"
                                            placeholder="Masukkan Jabatan">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="unit_kerja">Unit Kerja</label>
                                        <input type="text" class="form-control" id="unit_kerja" name="unit_kerja"
                                            value="{{ auth()->user()->unit_kerja }}" placeholder="Masukkan Unit Kerja">
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" data-bs-toggle="collapse"
                                    data-bs-target="#collapseChangePassword" aria-expanded="false"
                                    aria-controls="collapseChangePassword" id="gen-info-change-password">
                                <label class="form-check-label" for="gen-info-change-password">Change
                                    password?</label>
                            </div>

                            <div class="collapse" id="collapseChangePassword">
                                <div class="card border shadow-none card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="password_lama" class="form-label">Password Sekarang</label>
                                                <input type="password" class="form-control"
                                                    placeholder="Masukkan Password Sekarang" id="password_lama"
                                                    name="password_lama">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="mb-3" id="pw_baru">
                                                <label for="password_baru" class="form-label">Password Baru</label>
                                                <input type="password" class="form-control"
                                                    placeholder="Masukkan Password Baru" id="password_baru"
                                                    name="password_baru">
                                                <div class="error text-danger" id="password1"></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="mb-3" id="pw_konfirmasi">
                                                <label for="confirm_password" class="form-label">Konfirmasi
                                                    Password</label>
                                                <input type="password" class="form-control"
                                                    placeholder="Masukkan Konfirmasi Password" id="confirm_password"
                                                    onchange="check_pass()" name="confirm_password">
                                                <div class="error text-danger" id="password2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" id="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const form = document.getElementById("myForm");
        var feedback1 = document.getElementById("password1");
        var feedback2 = document.getElementById("password2");

        function check_pass() {
            if (document.getElementById('password_baru').value ==
                document.getElementById('confirm_password').value) {
                feedback1.innerHTML = ""
                feedback2.innerHTML = ""
                document.getElementById('submit').disabled = false;
                document.getElementById('password_baru').classList.remove("is-invalid")
                document.getElementById('confirm_password').classList.remove("is-invalid")
            } else {
                feedback1.innerHTML = "Password tidak cocok."
                feedback2.innerHTML = "Password tidak cocok."
                document.getElementById('submit').disabled = true;
                document.getElementById('password_baru').classList.add("is-invalid")
                document.getElementById('confirm_password').classList.add("is-invalid")
            }
        }
    </script>
@endsection
