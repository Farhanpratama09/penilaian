@extends('admin.layouts.main')

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

        <!-- Basic Bootstrap Table -->
        <div class="card">
            <h5 class="card-header">Kriteria Penilaian</h5>
            <div class="table-responsive text-nowrap">
                <table id="table-1" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kriteria</th>
                            <th>Bobot</th>
                            <td>Edit</td>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($kriteria as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kriteria }}</td>
                                <td>{{ $item->bobot }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#edit-{{ $item->id }}">
                                        <i class="bx bx-edit-alt me-1"></i>
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('detail_kriteria', $item->id) }}"
                                        class="bx bxs-right-arrow-square me-1"></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @foreach ($kriteria as $item)
        {{-- Modal edit anchor --}}
        <form action="{{ route('update_kriteria', $item->id) }}" method="post">
            @method('put')
            @csrf
            <div class="modal fade" id="edit-{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCenterTitle">Edit Kriteria</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="kriteria" class="col-sm-2 col-form-label">Kriteria</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="kriteria-{{ $item->id }}"
                                        name="kriteria" value="{{ $item->kriteria }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="bobot" class="col-sm-2 col-form-label">Bobot</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="bobot-{{ $item->id }}"
                                        name="bobot" step="0.01" min="0" max="1"
                                        value="{{ $item->bobot }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        {{-- End modal --}}
    @endforeach
    <style>
        /* CSS untuk kolom aksi */
        .table th:last-child,
        .table td:last-child {
            width: 80px;
            text-align: center;
        }

        .table td:last-child {
            padding: 0.5rem;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#table-1').DataTable({
                // Custom configuration for DataTables
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "lengthChange": false,
                "autoWidth": false
            });
            $('input[name="bobot"]').on('input', function() {
                const totalBobot = parseFloat($('#total-bobot').val());
                const inputs = $('input[name="bobot"]');
                let total = 0;

                inputs.each(function() {
                    total += parseFloat($(this).val()) || 0;
                });

                if (total > 1) {
                    alert('Total bobot tidak boleh lebih dari 100%');
                    $(this).val(0);
                    total -= parseFloat($(this).val());
                }

                inputs.not(this).each(function() {
                    const value = parseFloat($(this).val()) || 0;
                    $(this).val((value / total * (totalBobot - parseFloat($(this).val()))).toFixed(
                        2));
                });

                $('#total-bobot').val(total);
            });
        });
    </script>
@endsection
