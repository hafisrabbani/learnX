@extends('layouts.main')
@section('_title', 'Manage Users')
@section('page-heading','Manage Users')
@section('css')
<link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection
@section('page-content')
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-sm mb-3 btn-primary" onclick="showModal('create')">
                    <i class="bi bi-plus-circle"></i> Tambah User
                </button>
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nrp</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $key => $val)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $val['nrp'] ?? '-' }}</td>
                            <td>{{ $val['name'] }}</td>
                            <td>{{ $val['email'] }}</td>
                            <td class="text-capitalize">
                                @php $roles = $val['role']; @endphp
                                @if($roles == 'admin')
                                <span class="badge bg-primary">{{ $roles }}</span>
                                @elseif($roles == 'dosen')
                                <span class="badge bg-success">{{ $roles }}</span>
                                @else
                                <span class="badge bg-danger">{{ $roles }}</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick="showModal('edit', {{ $val['id'] }})"><i
                                        class="bi bi-pencil-square"></i></button>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="deleteData({{ $val['id'] }})"><i class="bi bi-trash"></i></button>
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
                        <label for="nrp">Nrp</label>
                        <input type="text" name="name" id="nrp" placeholder="Nrp" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" placeholder="Email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Password"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control">
                            <option value="" selected disabled>-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="dosen">Dosen</option>
                            <option value="mahasiswa">Mahasiswa</option>
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
<script>
    $(document).ready(function () {
        $('#table').DataTable({
            "order": [
                [0, "asc"]
            ],
            "columnDefs": [{
                "targets": [4],
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
                        text: error,
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
            $('#form').attr('action', '{{ route("user-manage.create") }}');
            $('#form').append('<input type="hidden" name="_method" value="POST">');
            $('#modal').modal('show');
        } else {
            $('#modal-title').html('Edit User');
            $('#form').attr('action', '{{ route("user-manage.update", ":id") }}'.replace(':id', id));
            $('#form').append('<input type="hidden" name="_method" value="POST">');
            $.ajax({
                url: "{{ route('user-manage.edit', ':id') }}".replace(':id', id),
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    var result = data.data;
                    $('#nrp').val(result.nrp);
                    $('#name').val(result.name);
                    $('#email').val(result.email);
                    $('#role').val(result.role);
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
                    url: "{{ route('user-manage.delete', ':id') }}".replace(':id', id),
                    method: 'DELETE',
                    dataType: 'json',
                    success: function (data) {
                        swal({
                            title: "Success!",
                            text: data.message,
                            icon: "success",
                            button: "OK",
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