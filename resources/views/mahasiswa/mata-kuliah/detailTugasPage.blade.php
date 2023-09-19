@extends('layouts.main')

@section('_title', 'DETAIL TUGAS')
@section('page-heading', 'DETAIL TUGAS')

{{-- id tugas, id mahasiswa, tugas, assignment --}}
@section('page-content')
    <section class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold pb-2">{{ $tugas->judul_tugas }}</h5>

                    <h6 class="fw-medium">deskripsi : </h6>
                    <p class="text-muted">
                        {{ $tugas->deskripsi }}
                    </p>
                    <h6 class="fw-medium">deadline : </h6>
                    <p class="badge badge-primary bg-primary pb-2">
                        {{ date('d/m/Y H:i:s', strtotime($tugas->deadline)) }}
                    </p>
                    <h6 class="fw-medium pt-2">lihat penugasan : </h6>
                    <a href="{{ asset('storage/tugas/' . $tugas->attachment) }}" target="_blank"
                        class="btn btn-sm btn-primary">lihat</a>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        @if ($status != '')
                            <button type="button" style="width:100%;
                        display:block;"
                                class="btn btn-sm btn-secondary mt-3" disabled>sudah submit</button>
                            <button type="submit" style="width:100%;
                            display:block;"
                                class="btn btn-sm btn-primary mt-3"
                                onclick="showModal({{ $tugas->id }} ,{{ $submission->id }})">edit
                                tugas</button>
                        @else
                            <form enctype="multipart/form-data" method="POST"
                                action="{{ route('mahasiswa.tugas.createSubmission') }}" id="form">
                                @csrf
                                <label for="attachment">attachment</label>
                                <input type="file" name="attachment" id="attachment" class="form-control">

                                <label for="komentar" class="pt-2">komentar</label>
                                <textarea type="textarea" name="komentar" id="komentar" class="form-control"></textarea>

                                <input type="hidden" name="id_tugas" value="{{ $tugas->id }}">
                                <button type="submit" style="width:100%;
                            display:block;"
                                    class="btn btn-sm btn-primary mt-3">submit
                                    tugas</button>
                            </form>
                        @endif

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
                    <form id="form" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="komentar">komentar</label>
                                <input type="text" name="komentar" id="komentar" placeholder="komentar..."
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="attachment">attachment</label>
                                <input type="file" name="attachment" id="attachment" placeholder="attachment..."
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

    </section>
@endsection

@push('js')
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#form').on('submit', function(e) {
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
                    success: function(data) {
                        swal({
                            title: data.message.title,
                            text: data.message.subtitle,
                            icon: "success",
                            button: "OK",
                        }).then((value) => {
                            window.location.reload();
                        });
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

            })
        })

        function showModal(id_tugas, id_submission) {
            $('#modal-title').html('Edit Tugas');
            $('.warn').remove();
            $('#form').attr('action',
                '{{ route('mahasiswa.tugas.updateSubmission', ':id_submission') }}'
                .replace(':id_submission', id_submission));
            $('#form').append('<input type="hidden" name="_method" value="POST">');
            $('#modal').modal('show');
            $.ajax({
                url: "{{ route('mahasiswa.tugas.editSubmission', ':id_tugas') }}"
                    .replace(':id_tugas', id_tugas),
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    var result = data.data;
                    console.log(data);
                    $('#komentar').val(result.komentar);
                    var warn =
                        '<small class="text-danger warn">*kosongkan jika tidak ingin mengubah file</small>';
                    $('#files').after(warn);
                    $('#modal').modal('show');
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
