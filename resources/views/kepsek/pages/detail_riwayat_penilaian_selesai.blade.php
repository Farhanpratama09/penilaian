@extends('kepsek.layouts.main')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Penilaian Guru</h4>
            </div><!-- end card header -->
        </div>
        <div class="card-body">
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
                                    <li class="fw-bold">
                                        <p class="fw-bold">
                                            {{ $sub_kriteria->sub_kriteria }}</p>
                                        @foreach ($sub_kriteria->anchor as $anchor)
                                            @if (in_array($anchor->id, $anchor_hasil))
                                                @php
                                                    $key = array_search($anchor->id, $anchor_hasil);
                                                @endphp
                                                <div class="row">
                                                    <div class="col-1 fw-normal" style="width: 40px;">
                                                        ({{ $anchor->bobot }})
                                                    </div>
                                                    <div class="col-1" style="width: 20px;">
                                                        <input type="radio"
                                                            name="penilaian[sub-kriteria-{{ $sub_kriteria->id }}]"
                                                            value="{{ $anchor->id }}" checked required>
                                                    </div>
                                                    <div class="col-10 fw-normal">
                                                        {{ $anchor->anchor }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-1 fw-normal" style="width: 40px;">
                                                        ({{ $anchor->bobot }})
                                                    </div>
                                                    <div class="col-1" style="width: 20px;">
                                                        <input type="radio" disabled
                                                            name="penilaian[sub-kriteria-{{ $sub_kriteria->id }}]"
                                                            value="{{ $anchor->id }}" required>
                                                    </div>
                                                    <div class="col-10 fw-normal">
                                                        {{ $anchor->anchor }}
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </li>
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
            <div class="d-flex justify-content-between align-items-start gap-3 mt-4">
                <button type="button" class="btn btn-primary w-sm" id="prevBtn" onclick="prevTab()">Previous</button>
                <button type="button" class="btn btn-primary w-sm ms-auto" id="nextBtn" onclick="nextTab()">Next</button>
            </div>
        </div>
    </div>
    <script src="https://preview.pichforest.com/dashonic/layouts/assets/js/pages/form-wizard.init.js"></script>
    <script>
        let currentTabIndex = 0;

        function save() {
            document.getElementById("regForm").submit();
        }

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
                nextBtn.innerText = 'Submit';
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
