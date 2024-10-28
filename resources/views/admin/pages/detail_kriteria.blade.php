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

        <div class="card">
            <h5 class="card-header">Detail Kriteria Penilaian: <span class="text-black">{{ $kriteria->kriteria }}</span>
            </h5>
            <div class="table-responsive text-nowrap">
                <table id="table-1" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Sub Kriteria</th>
                            <th>Anchor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($kriteria->subKriteria as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->sub_kriteria }}</td>
                                <td>
                                    <ul>
                                        @foreach ($item->anchor as $anchor)
                                            <li>{{ $anchor->anchor }} ({{ $anchor->bobot }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#edit-{{ $item->id }}">
                                        <i class="bx bx-edit-alt me-1"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($kriteria->subKriteria as $item)
        {{-- Modal edit anchor --}}
        <form action="{{ route('edit_anchor', $item->id) }}" method="post">
            @method('put')
            @csrf
            <div class="modal fade" id="edit-{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCenterTitle">Edit Anchor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3"><b>Anchor</b></div>
                                <div class="col-2"><b>Bobot</b></div>
                            </div>
                            @foreach ($item->anchor as $anchor)
                                <div class="row mb-3">
                                    <div class="col-10">
                                        <textarea name="anchor[]" id="anchor" cols="55" rows="5">{{ $anchor->anchor }}</textarea>
                                    </div>
                                    <div class="col-2">
                                        <input type="text" class="form-control" id="bobot" name="bobot[]"
                                            value="{{ $anchor->bobot }}" readonly>
                                    </div>
                                    <input type="hidden" id="anchor_id" name="anchor_id[]" value="{{ $anchor->id }}"
                                        readonly>
                                </div>
                            @endforeach
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

        /* CSS untuk modal */
        .modal-dialog {
            max-width: 50%;
        }

        .modal-content {
            border-radius: 0.5rem;
        }

        /* CSS untuk modal body */
        .modal-body {
            padding: 1rem;
        }

        .modal-body .row {
            display: flex;
            align-items: center;
        }

        .modal-body .col-10 {
            flex: 1;
        }

        .modal-body .col-2 {
            max-width: 150px;
            padding-left: 1rem;
        }

        .modal-body textarea {
            width: 100%;
            box-sizing: border-box;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#table-1').DataTable();
        });
    </script>
@endsection
