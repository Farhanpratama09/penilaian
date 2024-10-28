@extends('guru.layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex">
                    <a class="btn btn-success me-3" href="{{ route('riwayat_penilaian') }}">Kembali</a>
                    <h4 class="card-title">Form Penilaian Guru</h4>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <!-- wizard-nav -->
                <div class="tab-content" id="pills-tabContent">
                    @foreach ($kriteria as $item)
                        <div class="tab-pane fade @if ($loop->first) show active @endif"
                            id="pills-step-{{ $loop->iteration }}" role="tabpanel"
                            aria-labelledby="pills-step-{{ $loop->iteration }}-tab">
                            <div class="text-center mb-4">
                                <h3>{{ $item->kriteria }}</h3>
                                <p class="card-title-desc">Detail Riwayat Penilaian</p>
                                <!-- Button to trigger modal for Expectation and Feedback -->
                                <button type="button" class="btn btn-link" data-bs-toggle="modal"
                                    data-bs-target="#modalExpectationFeedback-{{ $item->id }}">
                                    Lihat Ekspektasi dan Umpan Balik
                                </button>
                            </div>
                            <div>
                                <ol type="A">
                                    @foreach ($item->subKriteria as $sub_kriteria)
                                        @php
                                            $key = -1;
                                        @endphp
                                        <li class="fw-bold mb-3">
                                            <p class="fw-bold mb-2">
                                                {{ $sub_kriteria->sub_kriteria }}</p>
                                            @foreach ($sub_kriteria->anchor as $anchor)
                                                @if (in_array($anchor->id, $anchor_hasil))
                                                    @php
                                                        $key = array_search($anchor->id, $anchor_hasil);
                                                    @endphp
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="me-2 fw-normal" style="width: 40px;">
                                                            ({{ $anchor->bobot }})
                                                        </div>
                                                        <div class="me-2" style="width: 20px;">
                                                            <input type="radio" disabled
                                                                name="penilaian[{{ $sub_kriteria->id }}]"
                                                                value="{{ $anchor->id }}" checked required>
                                                        </div>
                                                        <div class="col-9 fw-normal">
                                                            {{ $anchor->anchor }}
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="me-2 fw-normal" style="width: 40px;">
                                                            ({{ $anchor->bobot }})
                                                        </div>
                                                        <div class="me-2" style="width: 20px;">
                                                            <input type="radio" disabled
                                                                name="penilaian[{{ $sub_kriteria->id }}]"
                                                                value="{{ $anchor->id }}" required>
                                                        </div>
                                                        <div class="col-9 fw-normal">
                                                            {{ $anchor->anchor }}
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </li>
                                        @if ($sub_kriteria->id == 1)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Rencana
                                                Pembelajaran, Buku Catatan Kelas, Dokumen Pemberitahuan dan Informasi,
                                                dan Foto
                                                Dokumentasi
                                            </div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 1)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif($sub_kriteria->id == 2)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Dokumen Catatan
                                                Perkembangan Siswa, Buku Catatan dan Rencana Pelajaran, Surat
                                                Rekomendasi dari
                                                Pimpinan Sekolah/Kolega</div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 2)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif($sub_kriteria->id == 3)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Laporan Hasil
                                                Evaluasi
                                                Pembelajaran, Catatan
                                                Refleksi - Umpan Balik Rekan Kerja, Hasil Evaluasi</div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 3)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 4)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Laporan Evaluasi
                                                Kinerja, Surat Pernyataan Kesediaan untuk Mematuhi Peraturan/Tata Tertib
                                                Sekolah, Bukti Absensi
                                            </div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 4)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 5)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Dokumen
                                                Pembelian dan Pengadaan, Bukti Pengakuan/Penghargaan, Laporan penggunaan
                                                dana Bantuan Operasional Sekolah, Bukti pemanfaatan teknologi informasi
                                                dalam mengelola barang milik negara
                                            </div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 5)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 6)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Surat Pernyataan
                                                Kesesuaian, Dokumen Pelatihan Etika dan Integritas, Surat Pernyataan
                                                Tidak
                                                Menyalahgunakan Kewenangan Jabatan, Surat Pernyataan Keabsahan Data,
                                                Laporan
                                                Kegiatan
                                            </div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 6)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 7)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Sertifikat
                                                Pelatihan, Bukti Partisipasi dalam Seminar atau Workshop, Rekomendasi
                                                dari Atasan atau Rekan Kerja, Bukti Penggunaan
                                                Teknologi
                                            </div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 7)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 8)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Bukti
                                                Sertifikasi Sebagai
                                                Fasilitator Pelatihan, Rekaman
                                                Pembelajaran Kelompok, Surat
                                                Rekomendasi
                                            </div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 8)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 9)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Rekomendasi dari
                                                Atasan
                                                Evaluasi Kinerja, Sertifikasi atau Penghargaan, Portofolio Karya Siswa,
                                                Laporan
                                                Pengawasan atau Pengamatan
                                            </div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 9)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 10)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Catatan Observasi
                                                Kelas,
                                                Evaluasi Dari Siswa, Bukti Hasil Survei Kepuasan Orang Tua Siswa dan
                                                Siswa
                                            </div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 10)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 11)
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 11)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 12)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Surat penugasan
                                                sebagai
                                                koordinator kegiatan, Sertifikat pelatihan atau penghargaan atas
                                                kontribusi
                                                dalam
                                                membangun budaya sekolah yang harmonis dan produktif, Program
                                                Pengembangan
                                                Kepribadian, Bukti Keterlibatan, Laporan atau artikel yang dihasilkan
                                                dari
                                                pengalaman dan observasi guru</div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 12)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 13)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Surat Pernyataan
                                                Setia
                                                pada
                                                Pancasila, Sertifikat dan Piagam Penghargaan, Surat pernyataan kesediaan
                                                menjadi guru yang dilampiri dengan salinan Kartu Tanda Penduduk (KTP)
                                                atau Kartu
                                                Keluarga (KK) sebagai bukti identitas, Surat tanda registrasi guru yang
                                                dikeluarkan oleh pemerintah setempat, Surat keputusan atau SK
                                                pengangkatan
                                                sebagai guru, Bukti partisipasi dalam kegiatan-kegiatan nasional</div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 13)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 14)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Surat Keputusan
                                                Penetapan
                                                Pegawai Negeri Sipil (PNS), Surat keputusan atau SK pengangkatan guru
                                                yang
                                                telah disahkan oleh pihak berwenang</div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 14)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 15)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Surat pernyataan
                                                yang
                                                berisi
                                                komitmen guru untuk menjaga nama baik instansi dan negara serta menjaga
                                                rahasia
                                                jabatan, Laporan bulanan atau tahunan mengenai kegiatan guru</div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 15)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 16)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Surat rekomendasi
                                                dari
                                                pimpinan atau atasan, Sertifikat atau bukti pelatihan, Bukti hasil
                                                inovasi atau
                                                penelitian, Dokumen hasil observasi guru dalam kegiatan pembelajaran di
                                                kelas
                                            </div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 16)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 17)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Bukti hasil
                                                penelitian
                                                atau publikasi karya ilmiah tentang inovasi dan kreativitas dalam
                                                pembelajaran,
                                                Dokumen rencana pelaksanaan pembelajaran (RPP), Portofolio karya-karya
                                                yang
                                                menunjukkan penggunaan media pembelajaran, Bukti partisipasi dalam
                                                pelatihan
                                                atau workshop, Dokumen refleksi diri atau jurnal, Bukti penghargaan atau
                                                sertifikat atas kontribusi guru dalam pengembangan inovasi dan
                                                kreativitas di
                                                bidang pendidikan </div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 17)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 19)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Laporan hasil
                                                kerja sama
                                                dengan para pengajar atau ahli di bidang tertentu, Surat penghargaan
                                                atau
                                                prestasi
                                                yang diperoleh oleh guru, Dokumen kerjasama dengan instansi atau lembaga
                                                di luar
                                                sekolah</div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 19)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 20)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Bukti hasil
                                                kerjasama
                                                antar guru, Dokumen hasil kerjasama antar guru dengan pihak-pihak lain,
                                                seperti
                                                pengusaha atau tokoh masyarakat, Dokumen program pengembangan diri guru
                                                dalam
                                                hal
                                                kerjasama dan kemitraan dengan berbagai pihak, seperti pelatihan
                                                kolaborasi dan
                                                kemitraan, Surat penghargaan atau testimoni dari pihak lain yang bekerja
                                                sama
                                                dengan guru dalam berbagai proyek atau kegiatan sekolah</div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 20)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($sub_kriteria->id == 21)
                                            <div class="mb-3 mt-2 text-muted">Berkas yang diperlukan: Dokumen rencana
                                                pembelajaran,
                                                Dokumen evaluasi, Bukti bahwa seorang guru memfasilitasi siswa untuk
                                                memanfaatkan
                                                media sosial atau platform pembelajaran online sebagai sarana belajar
                                                dan
                                                berbagi
                                                informasi dengan siswa di sekolah lain atau luar negeri, Bukti bahwa
                                                seorang
                                                guru
                                                mengajak para siswa dan staf sekolah untuk memanfaatkan fasilitas
                                                perpustakaan,
                                                laboratorium, dan sarana olahraga di sekolah secara maksimal dan
                                                teratur,
                                                Rekaman
                                                rapat koordinasi antara guru-guru di sekolah</div>
                                            <div class="my-3">
                                                @foreach ($berkas as $sub)
                                                    @if ($sub->sub_kriteria_id == 21)
                                                        @foreach ($sub->dokumen as $dokumen)
                                                            <div class="row mb-3">
                                                                <div class="col-3">
                                                                    <span class="fw-bold">
                                                                        {{ $dokumen['nama'] }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-3">
                                                                    <span>
                                                                        <a href="{{ asset('storage/' . $dokumen['path']) }}"
                                                                            class="btn btn-success btn-sm"
                                                                            target="_blank"><i
                                                                                class="uil uil-eye"></i>Lihat</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        <label for="catatan-{{ $sub_kriteria->id }}">Catatan</label>
                                        @foreach ($sub_kriteria->anchor as $anchor)
                                            @if (in_array($anchor->id, $anchor_hasil))
                                                <textarea name="catatan[{{ $anchor->id }}]" id="catatan-{{ $sub_kriteria->id }}" rows="5"
                                                    class="form-control mb-3" readonly>{{ $key > -1 ? $catatan[$key] : '' }}</textarea>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </ol>
                            </div>
                             <!-- Modal for Expectation and Feedback -->
                        <div class="modal fade" id="modalExpectationFeedback-{{ $item->id }}" tabindex="-1"
                            aria-labelledby="modalExpectationFeedbackLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalExpectationFeedbackLabel">Ekspektasi dan Umpan
                                            Balik</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Ekspektasi:</strong></p>
                                        <p>{{ $ekspektasi[$item->id] ?? 'Belum ada ekspektasi.' }}</p>
                                        <p><strong>Umpan Balik:</strong></p>
                                        <p>{{ $umpan_balik[$item->id] ?? 'Belum ada umpan balik.' }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="tahun" value="{{ $penilaian->tahun_penilaian->id }}">
                <input type="hidden" name="periode" value="{{ $penilaian->periode_penilaian->id }}">
                <div class="d-flex justify-content-between align-items-start gap-3 mt-4">
                    <button type="button" class="btn btn-primary w-sm" id="prevBtn"
                        onclick="prevTab()">Previous</button>
                    <button type="button" class="btn btn-primary w-sm ms-auto" id="nextBtn"
                        onclick="nextTab()">Next</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentTabIndex = 0;

        // function save() {
        //     document.getElementById("regForm").submit();
        // }

        function showTab(index) {
            const tabs = document.querySelectorAll('.tab-pane');

            tabs.forEach((tab, i) => {
                if (i === index) {
                    tab.classList.add('show', 'active');
                } else {
                    tab.classList.remove('show', 'active');
                }
            });

            fixButtonState();
        }

        function nextTab() {
            if (currentTabIndex < document.querySelectorAll('.tab-pane').length - 1) {
                currentTabIndex++;
                showTab(currentTabIndex);
            } else {
                save();
            }
        }

        function prevTab() {
            if (currentTabIndex > 0) {
                currentTabIndex--;
                showTab(currentTabIndex);
            }
        }

        function fixButtonState() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            if (currentTabIndex === 0) {
                prevBtn.style.display = 'none';
            } else {
                prevBtn.style.display = 'inline-block';
            }


            if (currentTabIndex === document.querySelectorAll('.tab-pane').length - 1) {
                nextBtn.style.display = 'none';
            } else {
                nextBtn.innerText = 'Next';
                nextBtn.style.display = 'inline-block';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            showTab(currentTabIndex);
        });
    </script>
@endsection
