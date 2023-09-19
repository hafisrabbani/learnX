@extends('layouts.main')

@section('_title', 'Detail Tugas')
@section('page-heading', 'Detail Tugas')

@section('page-content')
<section class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-body">

                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama Mahasiswa</th>
                            <th scope="col">Attachment</th>
                            <th scope="col">Status</th>
                            <th scope="col">Plagiarisme</th>
                            <th scope="col">Nilai</th>
                            <th scope="col">aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($submissions as $submission)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $submission->mahasiswa($submission->id_mahasiswa)->first()->name }}</td>
                            <td>
                                {{-- download submission --}}
                                <a href="{{ route('utils.download-tugas',$submission->attachment) }}" target="_blank"
                                    class="btn btn-sm btn-warning">download</a>
                            </td>
                            <td>
                                {{-- cek terlambat atau tidak --}}
                                @if ($submission->created_at > $deadline)
                                terlambat
                                @else
                                tepat waktu
                                @endif
                            </td>
                            <td>{{ $submission->plagiarism }}</td>
                            <td>
                                {{-- cek null atau tidak --}}
                                @if ($submission->nilai != 0)
                                {{ $submission->nilai }}
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                @if ($submission->nilai != 0)
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick="showModal('edit' , {{ $submission->id_tugas }}, {{ $submission->id_mahasiswa }})"><i
                                        class="fa fa-pencil"></i></button>
                                @else
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick="showModal('create',{{ $submission->id_tugas }}, {{ $submission->id_mahasiswa }})"><i
                                        class="fa fa-pencil"></i></button>
                                @endif

                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="checkingPlagiarism('{{$submission->attachment}}',{{ $id }}, '{{ $submission->mahasiswa->name}}')"><i
                                        class="fa fa-search"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
                    <form id="form" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nilai">Nilai</label>
                                <input type="number" name="nilai" id="nilai" placeholder="Nilai..."
                                    class="form-control">
                            </div>
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
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Detail</h5>
                <p class="card-text" id="name-compare"></p>
                <table class="table" id="table-plagiarism">
                    <thead>
                        <tr>
                            <th scope="row">Nama</th>
                            <th scope="row">Plagiarisme</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {
        $('#table-plagiarism').hide();
        $('#form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (data) {
                    swal({
                        title: "Success!",
                        text: data.message,
                        icon: "success",
                        button: "OK",
                    }).then((value) => {
                        window.location.reload();
                    });
                },
                error: function (xhr, status, error) {
                    swal({
                        title: "Error!",
                        text: error,
                        icon: "error",
                        button: "OK",
                    });
                }
            })

        })
    })

    function showModal(type, id_tugas, id_mahasiswa) {
        if (type == 'create') {
            $('#modal-title').html('Beri Nilai');
            $('#nilai').val('');
            $('#form').attr('action',
                '{{ route("dosen.detail-tugas.createNilai", ['id_tugas' => ': id_tugas', 'id_mahasiswa' => ': id_mahasiswa']) }}'
                    .replace(':id_tugas', id_tugas).replace(':id_mahasiswa', id_mahasiswa));

            $('#form').append('<input type="hidden" name="_method" value="POST">');
            $('#modal').modal('show');
        } else {
            $('#modal-title').html('Edit Nilai');
            $('#form').attr('action',
                '{{ route("dosen.detail-tugas.updateNilai", ['id_tugas' => ': id_tugas', 'id_mahasiswa' => ': id_mahasiswa']) }}'
                    .replace(':id_tugas', id_tugas).replace(':id_mahasiswa', id_mahasiswa));

            $('#form').append('<input type="hidden" name="_method" value="POST">');
            $('#modal').modal('show');
            $.ajax({
                url: '{{ route("dosen.detail-tugas.editNilai", ['id_tugas' => ': id_tugas', 'id_mahasiswa' => ': id_mahasiswa']) }}'
                        .replace(':id_tugas', id_tugas).replace(':id_mahasiswa', id_mahasiswa),
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    var result = data.data;
                    console.log(data);
                    $('#nilai').val(result.nilai);
                    $('#modal').modal('show');
                },
                error: function (xhr, status, error) {
                    swal({
                        title: "Error!",
                        text: error,
                        icon: "error",
                        button: "OK",
                    });
                }
            })
        }
    }

    function checkingPlagiarism(file, id_submission, name) {
        $('#table-plagiarism tbody').html('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: '{{ route("dosen.tugas.checkingPlagiarism")}}',
            method: 'POST',
            data: {
                files_compare: file,
                id_tugas: id_submission
            },
            success: function (data) {
                $('#table-plagiarism').show();
                swal.close();
                var response = data.data;
                var namecompare = $('#name-compare').html('Nama : <b>' + name + '</b>');
                var html = '';
                $.each(response.compared_files, function (index, value) {
                    html += '<tr>';
                    html += '<td>' + value.nama + '</td>';
                    html += '<td>' + value.similarity + '%</td>';
                    html += '</tr>';
                })
                $('#table-plagiarism tbody').html(html);
            },
            beforeSend: function () {
                swal({
                    title: "Loading!",
                    text: "Please wait",
                    icon: "info",
                    button: false,
                });
            },
            error: function (xhr, status, error) {
                console.log(error);
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