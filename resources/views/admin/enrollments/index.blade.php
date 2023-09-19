@extends('layouts.main')
@section('_title', 'Manage Enrollment')
@section('page-heading','Manage Enrollment')
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
                <button type="button" class="btn btn-sm mb-3 btn-primary" onclick="showModal('create')">
                    <i class="bi bi-plus-circle"></i> Tambah Enrollment
                </button>
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Matkul</th>
                            <th scope="col">Mahasiswa</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $key => $val)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge badge-primary bg-primary">{{ $val->matkul->kode_mk }}</span> <span
                                    class="badge badge-success bg-success">{{ $val->matkul->nama_mk }}</span></td>
                            <td>{{ $val->mahasiswa->name }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick="showModal('edit', {{ $val['id'] }})"><i
                                        class="bi bi-pencil-square"></i></button>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="deleteData({{ $val['id'] }})"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</section>

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
            <form id="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_user">Mahasiswa</label>
                        <select name="id_user" id="id_user" class="form-control">
                            <option value="" selected disabled>-- Pilih Mahasiswa --</option>
                            @foreach($mahasiswas as $key => $val)
                            <option value="{{ $val['id'] }}">{{ $val['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_matkul">Mata Kuliah</label>
                        <select name="id_matkul" id="id_matkul" class="form-control">
                            <option value="" selected disabled>-- Pilih Mata Kuliah --</option>
                            @foreach($mataKuliahs as $key => $val)
                            <option value="{{ $val['id'] }}">{{ $val['kode_mk'] }}/{{ $val['nama_mk'] }}</option>
                            @endforeach
                        </select>
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
<script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('#table').DataTable({
            "order": [
                [0, "asc"]
            ],
            "columnDefs": [{
                "targets": [3],
                "orderable": false
            }]
        });
        $('#form').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serializeArray(),
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
            $('#form').attr('action', '{{ route("enrollment.create") }}');
            $('#form').append('<input type="hidden" name="_method" value="POST">');
            $('#modal').modal('show');
        } else {
            $('#modal-title').html('Edit User');
            $('#form').attr('action', '{{ route("enrollment.update", ":id") }}'.replace(':id', id));
            $('#form').append('<input type="hidden" name="_method" value="POST">');
            $.ajax({
                url: "{{ route('enrollment.edit', ':id') }}".replace(':id', id),
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    var result = data.data;
                    $('#id_user').val(result.id_user);
                    $('#id_matkul').val(result.id_matkul);
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
                    url: "{{ route('enrollment.delete', ':id') }}".replace(':id', id),
                    method: 'DELETE',
                    dataType: 'json',
                    success: function (data) {
                        swal({
                            title: "Success!",
                            text: data.message,
                            icon: "success",
                            button: "OK",
                        }).then((value) => {
                            window.singleCheckPlagiarismoad();
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
            }
        });
    }
</script>
@endpush