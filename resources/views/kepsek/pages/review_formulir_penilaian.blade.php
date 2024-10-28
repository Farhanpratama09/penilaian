@extends('kepsek.layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Penilaian Guru</h4>
            </div><!-- end card header -->
            <div class="card-body">
                <form action="{{ route('accept_formulir', $penilaian->id) }}" method="POST" enctype="multipart/form-data"
                    id="regForm">
                    @csrf
                    @method('put')
                    <!-- wizard-nav -->
                    <div class="tab-content" id="pills-tabContent">
                        @foreach ($kriteria as $item)
                            <div class="tab-pane fade @if ($loop->first) show active @endif"
                                id="pills-step-{{ $loop->iteration }}" role="tabpanel"
                                aria-labelledby="pills-step-{{ $loop->iteration }}-tab">
                                <div class="text-center mb-4">
                                    <h3>{{ $item->kriteria }}</h3>
                                    <p class="card-title-desc">Review Penilaian Guru Berikut ini</p>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                        data-bs-target="#modalEkspektasiUmpanBalik-{{ $loop->iteration }}">
                                        Tambah Ekspektasi dan Umpan Balik
                                    </button>
                                </div>
                                <div>
                                    <ol type="A">
                                        @foreach ($item->subKriteria as $sub_kriteria)
                                            @php
                                                $key = -1;
                                            @endphp
                                            <li class="fw-bold mb-3">
                                                <p class="fw-bold mb-2">{{ $sub_kriteria->sub_kriteria }}</p>
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
                                                                <input type="radio"
                                                                    name="penilaian[{{ $sub_kriteria->id }}]"
                                                                    value="{{ $anchor->id }}" checked required>
                                                            </div>
                                                            <div class="col-9 fw-normal">{{ $anchor->anchor }}</div>
                                                        </div>
                                                    @else
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div class="me-2 fw-normal" style="width: 40px;">
                                                                ({{ $anchor->bobot }})</div>
                                                            <div class="me-2" style="width: 20px;">
                                                                <input type="radio"
                                                                    name="penilaian[{{ $sub_kriteria->id }}]"
                                                                    value="{{ $anchor->id }}" required>
                                                            </div>
                                                            <div class="col-9 fw-normal">{{ $anchor->anchor }}</div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </li>
                                            <label for="catatan-{{ $sub_kriteria->id }}">Catatan</label>
                                            @foreach ($sub_kriteria->anchor as $anchor)
                                                @if (in_array($anchor->id, $anchor_hasil))
                                                    <textarea name="catatan[{{ $anchor->id }}]" id="catatan-{{ $sub_kriteria->id }}" rows="5"
                                                        class="form-control mb-3">{{ $key > -1 ? $catatan[$key] : '' }}</textarea>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </ol>
                                </div>
                            </div>

                            <!-- Modal untuk ekspektasi dan umpan balik -->
                            <div class="modal fade" id="modalEkspektasiUmpanBalik-{{ $loop->iteration }}" tabindex="-1"
                                aria-labelledby="modalEkspektasiUmpanBalikLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalEkspektasiUmpanBalikLabel">Ekspektasi dan Umpan
                                                Balik untuk {{ $item->kriteria }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="ekspektasi-{{ $item->id }}"
                                                    class="form-label">Ekspektasi</label>
                                                <input type="text" class="form-control"
                                                    id="ekspektasi-{{ $item->id }}"
                                                    name="ekspektasi_pimpinan[{{ $item->id }}]"
                                                    placeholder="Masukkan ekspektasi" required>
                                            </div>

                                            <!-- Radio Buttons dengan opsi terakhir berupa TextArea -->
                                            <div class="mb-3">
                                                <label class="form-label">Umpan Balik</label>
                                                <div class="form-check">
                                                    <input class="form-check-input radio-umpan" type="radio"
                                                        name="umpan_balik[{{ $item->id }}]"
                                                        id="umpanBalikSangatBaik-{{ $item->id }}" value="Sangat Baik"
                                                        required>
                                                    <label class="form-check-label"
                                                        for="umpanBalikSangatBaik-{{ $item->id }}">
                                                        Sangat Baik
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input radio-umpan" type="radio"
                                                        name="umpan_balik[{{ $item->id }}]"
                                                        id="umpanBalikTingkatkan-{{ $item->id }}" value="Tingkatkan"
                                                        required>
                                                    <label class="form-check-label"
                                                        for="umpanBalikTingkatkan-{{ $item->id }}">
                                                        Tingkatkan
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input radio-umpan" type="radio"
                                                        name="umpan_balik[{{ $item->id }}]"
                                                        id="umpanBalikPertahankan-{{ $item->id }}" value="Pertahankan"
                                                        required>
                                                    <label class="form-check-label"
                                                        for="umpanBalikPertahankan-{{ $item->id }}">
                                                        Pertahankan
                                                    </label>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input radio-umpan" type="radio"
                                                        name="umpan_balik[{{ $item->id }}]"
                                                        id="umpanBalikLainnya-{{ $item->id }}" value="Lainnya"
                                                        required onchange="toggleTextarea({{ $item->id }}, true)">
                                                    <label class="form-check-label"
                                                        for="umpanBalikLainnya-{{ $item->id }}">Lainnya</label>
                                                </div>

                                                <div class="form-group mt-3">
                                                    <textarea class="form-control d-none" id="textareaLainnya-{{ $item->id }}"
                                                        name="umpan_balik_lainnya[{{ $item->id }}]" rows="3"></textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                                                    onclick="saveFeedback({{ $item->id }})">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
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
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentTabIndex = 0;

        function toggleTextarea(itemId, show) {
            const textarea = document.getElementById(`textareaLainnya-${itemId}`);
            if (show) {
                textarea.classList.remove('d-none');
                textarea.required = true; // Set textarea required jika "Lainnya" dipilih
            } else {
                textarea.classList.add('d-none');
                textarea.value = ''; // Reset value ketika hidden
                textarea.required = false; // Tidak required jika "Lainnya" tidak dipilih
            }
        }

        // Event listener untuk setiap radio button
        document.querySelectorAll('.radio-umpan').forEach(radio => {
            radio.addEventListener('change', function() {
                const itemId = this.name.split('[')[1].split(']')[0];
                if (this.value === 'Lainnya') {
                    toggleTextarea(itemId, true);
                } else {
                    toggleTextarea(itemId, false);
                }
            });
        });


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
                document.getElementById("regForm").submit();
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
                nextBtn.innerText = 'Submit';
            } else {
                nextBtn.innerText = 'Next';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            showTab(currentTabIndex);
        });
    </script>
@endsection
