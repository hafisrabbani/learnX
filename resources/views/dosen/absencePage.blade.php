@extends('layouts.main')

@section('_title', 'Absences')
@section('page-heading', 'Absences')

@section('page-content')
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-sm mb-4 btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
                        <i class="bi bi-plus-circle"></i> buat absensi
                    </button>


                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">tanggal</th>
                                <th scope="col">aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($absences as $absence)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $absence->updated_at }}</td>
                                    <td>
                                        <form action="" method="post" style="display: inline;">
                                            @csrf
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="showModal('{{ route('dosen.absences.getAbsenseDetail', ['id' => $absence->id]) }}')"><i
                                                    class="fa fa-eye"></i></button>
                                        </form>
                                        <form action="{{ route('dosen.absences.delete') }}" method="post"
                                            style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $absence->id }}">
                                            <button type="submit" class="btn btn-sm btn-danger"><i
                                                    class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

            {{-- READ MODAL --}}
            <div class="modal fade" id="readmodal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="read-title"></h5>
                            <table class="table" id="table">
                                <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Hadir</th>
                                    </tr>
                                </thead>
                                <tbody id="read-tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL --}}
            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-title"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('dosen.absences.store') }}">
                            @csrf
                            <div class="modal-body">
                                <h5 class="modal-title" id="modal-title">Buat Absensi</h5>
                                <p class="fs-6 text-danger">*check untuk siswa yang masuk</p>

                                @foreach ($students as $student)
                                    <div class="form-check">
                                        <input type="hidden" name="students[{{ $student['id'] }}]" value="false">
                                        <input class="form-check-input" type="checkbox" value="true"
                                            name="students[{{ $student['id'] }}]" id="student{{ $student['id'] }}">
                                        <label class="form-check-label" for="student{{ $student['id'] }}">
                                            {{ $student['name'] }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        @if (session('errors'))
            swal({
                title: 'Pesan!',
                text: '{{ session()->get('errors')->first() }}',
                icon: 'warning',
                showCancelButton: false,
                showConfirmButton: false,
                timer: 2000
            })
        @endif

        @if (session('success'))
            swal({
                title: 'Sukses!',
                text: '{{ session()->get('success') }}',
                icon: 'success',
                showCancelButton: false,
                showConfirmButton: false,
                timer: 2000
            })
        @endif

        @if (session('error'))
            {
                swal({
                    title: 'Gagal!',
                    text: '{{ session()->get('error') }}',
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        @endif
    </script>
    <script>
        function showModal(url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#read-tbody').empty();
                    $.each(data, function(index, item) {
                        var tr = $("<tr>");
                        tr.append($("<td>").text(index + 1));
                        tr.append($("<td>").text(item.name));
                        tr.append($("<td>").text(item.is_absence ? "Yes" :
                            "No"));
                        $('#read-tbody').append(tr);
                    });
                    $('#readmodal').modal('show');
                },
                error: function(xhr, status, error) {
                    swal({
                        title: "Error!",
                        text: error,
                        icon: "error",
                        button: "OK",
                    });
                }
            })

        }
    </script>
@endpush
