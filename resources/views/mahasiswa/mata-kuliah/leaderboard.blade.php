@extends('layouts.main')

@section('_title', 'Leaderboard')
@section('page-heading', 'Leaderboard')
@section('css')
<style>
    .bg-bronze {
        background-color: #cd7f32;
    }

    .badge-list {
        list-style: none;
        border-bottom: 1px solid #ccc;
        padding: 10px;
    }
</style>
@endsection
@section('page-content')
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h6 class="mb-3 text-capitalize">Mata kuliah : {{ $nama_matkul }}</>
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <th scope="col">Rank</th>
                                <th scope="col">Nama mahasiswa</th>
                                <th scope="col">Level</th>
                                <th scope="col">Lencana</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaderBoard as $item)
                            <tr>
                                <td scope="col">{{ $loop->iteration }}</td>
                                <td scope="col">{{ $item['nama_mahasiswa'] }}</td>
                                <td scope="col">
                                    <a data-toggle="tooltip" title="point : {{ $item['point'] }}">
                                        {{ $item['level'] }}</a>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary"
                                        onclick="showModal({{ $id_matkul }}, {{ $item['id_mahasiswa'] }})">
                                        <i class="bi bi-award-fill"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
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
                    <div class="modal-body">
                        <ul class="list-group" id="badgelist">

                        </ul>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
@push('js')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script>
    function buildBadgeLi(count, type, name) {
        return '<li class="badge-list">' + badgeType(type) + ' ' + count + ' ' + type + ' ' + name + '</li>';
    }

    function badgeType(type) {
        switch (type) {
            case 'gold':
            case 'Gold':
                return '<i class="bi bi-award-fill px-2 py-1 text-white rounded-circle bg-warning"></i>';
            case 'silver':
            case 'Silver':
                return '<i class="bi bi-award-fill px-2 py-1 text-white rounded-circle bg-secondary"></i>';
            case 'bronze':
            case 'Bronze':
                return '<i class="bi bi-award-fill px-2 py-1 text-white rounded-circle bg-bronze"></i>';
            default:
                return '';
        }
    }

    function showModal(id_matkul, id_mahasiswa) {
        $('#modal-title').html('Lencana');
        $('#modal').modal('show');
        $.ajax({
            url: "{{ route('mahasiswa.badge.getBadge', ['matkul_id' => ':id_matkul', 'mahasiswa_id' => ':id_mahasiswa']) }}"
                .replace(':id_matkul', id_matkul).replace(':id_mahasiswa', id_mahasiswa),
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                var result = data.data;
                var badgeHtml = '';

                console.log(data);

                if (result.length == 0) {
                    $('#badgelist').html('<div class="container"><h5 class="text-center">Tidak ada lencana</h5></div>');
                    $('#modal').modal('show');
                } else {
                    $.each(result, function (index, badge) {
                        badgeHtml += buildBadgeLi(badge.count, badge.type, badge.name);
                    });
                    $('#badgelist').html(badgeHtml);
                    $('#modal').modal('show');
                }
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
</script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
@endpush