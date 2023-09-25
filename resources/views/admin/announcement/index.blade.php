@extends('layouts.main')
@section('_title', 'Manage Announcement')
@section('page-heading', 'Manage Announcement')
@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection
@section('page-content')
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-sm mb-3 btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
                        <i class="bi bi-plus-circle"></i> Tambah Announcement
                    </button>
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">title</th>
                                <th scope="col">date</th>
                                <th scope="col">action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($announcements as $key => $val)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $val->title }}</td>
                                    <td>{{ $val->created_at }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary"
                                            onclick="showModal('{{ route('announcement.show', ['id' => $val->id]) }}')"><i
                                                class="fa fa-eye"></i></button>
                                        <form action="{{ route('announcement.delete') }}" method="post"
                                            style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $val->id }}">
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
        </div>
    </section>

    {{-- READ MODAL --}}
    <div class="modal fade" id="readmodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="read-title"></h2>
                </div>
                <div class="modal-body">
                    <div class="h6">description : </div>
                    <p id="read-description"></p>
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form" method="POST" action="{{ route('announcement.create') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Judul announcement</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Judul..">
                        </div>
                        <div class="form-group mt-2">
                            <label for="description">Deskripsi</label>
                            <input type="text" class="form-control" id="description" name="description"
                                placeholder="Deskripsi..">
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

    <script>
        function showModal(url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#read-title').text('title : ' + data.title);
                    $('#read-description').text(data.description);
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



{{-- @push('js')
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                "order": [
                    [0, "asc"]
                ],
                "columnDefs": [{
                    "targets": [3],
                    "orderable": false
                }]
            });
            $('#form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serializeArray(),
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
                            text: xhr.responseJSON.message,
                            icon: "error",
                            button: "OK",
                        });
                    }
                })

            })
        })

        function showModal(type, id = null) {
            if (type == 'create') {
                $('#modal-title').html('Tambah User');
                $('#form').attr('action', '{{ route('Announcement.create') }}');
                $('#form').append('<input type="hidden" name="_method" value="POST">');
                $('#modal').modal('show');
            } else {
                $('#modal-title').html('Edit User');
                $('#form').attr('action', '{{ route('Announcement.update', ':id') }}'.replace(':id', id));
                $('#form').append('<input type="hidden" name="_method" value="POST">');
                $.ajax({
                    url: "{{ route('Announcement.edit', ':id') }}".replace(':id', id),
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var result = data.data;
                        $('#id_user').val(result.id_user);
                        $('#id_matkul').val(result.id_matkul);
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
                        url: "{{ route('Announcement.delete', ':id') }}".replace(':id', id),
                        method: 'DELETE',
                        dataType: 'json',
                        success: function(data) {
                            swal({
                                title: "Success!",
                                text: data.message,
                                icon: "success",
                                button: "OK",
                            }).then((value) => {
                                window.singleCheckPlagiarismoad();
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
@endpush --}}
