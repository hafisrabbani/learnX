@extends('layouts.main')
@section('_title', 'Manage Mata Kuliah')
@section('page-heading','Manage Mata Kuliah')
@section('css')
<link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection
@section('page-content')
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-sm mb-3 btn-primary" onclick="showModal('create')">
                    <i class="bi bi-plus-circle"></i> Tambah MK
                </button>
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Kode MK</th>
                            <th scope="col">Nama MK</th>
                            <th scope="col">SKS</th>
                            <th scope="col">Dosen Pengampu</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matkuls as $key => $val)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $val['kode_mk'] }}</td>
                            <td>{{ $val['nama_mk'] }}</td>
                            <td>{{ $val['sks'] }}</td>
                            <td>{{ $val->dosen->name }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" onclick="showModal('edit', {{ $val['id'] }})"><i class="bi bi-pencil-square"></i></button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteData({{ $val['id'] }})"><i class="bi bi-trash"></i></button>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- MODAL -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
                        <label for="kode_mk">Kode MK</label>
                        <input type="text" class="form-control" id="kode_mk" name="kode_mk" placeholder="Kode MK">
                    </div>
                    <div class="form-group">
                        <label for="nama_mk">Nama MK</label>
                        <input type="text" class="form-control" id="nama_mk" name="nama_mk" placeholder="Nama MK">
                    </div>
                    <div class="form-group">
                        <label for="sks">SKS</label>
                        <input type="text" class="form-control" id="sks" name="sks" placeholder="SKS">
                    </div>
                    <div class="form-group">
                        <label for="dosen_id">Dosen Pengampu</label>
                        <select class="form-control" id="dosen_id" name="dosen_id">
                            <option value="" selected disabled>-- Pilih Dosen --</option>
                            @foreach($dosens as $key => $val)
                            <option value="{{ $val['id'] }}">{{ $val['name'] }}</option>
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
<script>
    $(document).ready(function() {
        $('#table').DataTable({
            "order": [
                [0, "asc"]
            ],
            "columnDefs": [{
                "targets": [5],
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
            $('#form').attr('action', '{{ route("mata-kuliah.create") }}');
            $('#form').append('<input type="hidden" name="_method" value="POST">');
            $('#modal').modal('show');
        } else {
            $('#modal-title').html('Edit User');
            $('#form').attr('action', '{{ route("mata-kuliah.update", ":id") }}'.replace(':id', id));
            $('#form').append('<input type="hidden" name="_method" value="POST">');
            $.ajax({
                url: "{{ route('mata-kuliah.edit', ':id') }}".replace(':id', id),
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    var result = data.data;
                    $('#kode_mk').val(result.kode_mk);
                    $('#nama_mk').val(result.nama_mk);
                    $('#sks').val(result.sks);
                    $('#dosen_id').val(result.dosen_id);
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
                    url: "{{ route('mata-kuliah.delete', ':id') }}".replace(':id', id),
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