@extends('layouts.main')

@section('_title', 'MATERI')
@section('page-heading', 'MATERI')

@section('page-content')
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-sm mb-4 btn-primary" onclick="showModal('create')">
                        <i class="bi bi-plus-circle"></i> Tambah Materi
                    </button>
                    <div class="row">
                        @foreach ($materis as $materi)
                            <div class="col-md-4">
                                <div class="card shadow p-1 mb-5 rounded" style="height: 15rem;">
                                    <div class="card-body">

                                        <div class="row align-items-center">
                                            <div class="col-10">
                                                <h5 class="fw-bold">{{ $materi['judul_materi'] }}</h5>
                                                <p class="text-muted">
                                                    {{ str_word_count($materi['deskripsi']) > 10
                                                        ? substr($materi['deskripsi'], 0, 50) . '...'
                                                        : $materi['deskripsi'] }}
                                                </p>
                                            </div>
                                            <div class="col-2">
                                                <div class="icon-container bg-{{ $randomIcon['color'] }} text-white float-end"
                                                    style="width: 3rem; height: 3rem; border-radius: 5px; display: flex; justify-content: center; align-items: center;">
                                                    <i class="{{ $randomIcon['icon'] }} icon" style="font-size: 2rem;"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="{{ route('utils.download-tugas', $materi->attachment) }}?type=modul"
                                            target=" _blank" class="btn btn-sm btn-warning"><i
                                                class="bi bi-eye-fill"></i></a>
                                        <button type="button" class="btn btn-sm btn-primary"
                                            onclick="showModal('edit', {{ $materi->id }})"><i
                                                class="bi bi-pencil-square"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="deleteData({{ $materi->id }})"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

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
                                <div class="form-group mb-2">
                                    <label for="judul_materi">Judul</label>
                                    <input type="text" name="judul_materi" id="judul_materi" placeholder="Judul materi.."
                                        class="form-control">
                                </div>
                                <div class="form-group mb-2">
                                    <input type="hidden" name="id_matkul" id="id_matkul" value="{{ $id }}"
                                        class="form-control">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="deskripsi">Deskripsi</label>
                                    <input type="text" name="deskripsi" id="deskripsi" placeholder="deskripsi.."
                                        class="form-control">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="files">Attachment</label>
                                    <input type="file" name="files" id="files" class="form-control">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="additional_reference">Referensi Tambahan (opsional)</label>
                                    <input type="text" name="additional_reference" id="additional_reference"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save
                                    changes</button>
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
                            title: "Success!",
                            text: data.message,
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
                    },
                    beforeSend: function() {
                        swal({
                            title: "Loading...",
                            text: "Please wait",
                            icon: "info",
                            buttons: false,
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                        });
                    },
                })

            })
        })

        function showModal(type, id = null) {
            if (type == 'create') {
                $('#modal-title').html('Tambah Materi');
                $('#form').attr('action', '{{ route('dosen.materi.createMateri') }}');
                $('#form').append('<input type="hidden" name="_method" value="POST">');
                $('#modal').modal('show');
            } else {
                $('#modal-title').html('Edit Materi');
                $('.warn').remove();
                $('#form').attr('action', "{{ route('dosen.materi.updateMateri', ':id') }}".replace(':id', id));
                $('#form').append('<input type="hidden" name="_method" value="POST">');
                $('#modal').modal('show');
                $.ajax({
                    url: "{{ route('dosen.materi.editMateri', ':id') }}".replace(':id', id),
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var result = data.data;
                        console.log(data);
                        $('#judul_materi').val(result.judul_materi);
                        $('#deskripsi').val(result.deskripsi);
                        $('#additional_reference').val(result.additional_reference);
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
        }

        function deleteData(id) {
            swal({
                title: "Apakah anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                buttons: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        url: "{{ route('dosen.materi.deleteMateri', ':id') }}".replace(':id', id),
                        method: 'DELETE',
                        dataType: 'json',
                        success: function(data) {
                            swal({
                                title: "Success!",
                                text: data.message,
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
                }
            });
        }
    </script>
@endpush
