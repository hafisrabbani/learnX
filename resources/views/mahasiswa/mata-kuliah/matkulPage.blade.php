@extends('layouts.main')

@section('_title', 'MATA KULIAH')
@section('page-heading', 'MATA KULIAH')

@section('page-content')
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama MK</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mataKuliahs as $mataKuliah)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $mataKuliah->nama_mk }}</td>
                            <td>
                                <a href="{{ route('mahasiswa.tugas.index', $mataKuliah->id) }}">
                                    <button type="button" class="btn btn-sm btn-primary">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </button>
                                </a>
                                <a href="{{ route('mahasiswa.materi.index', $mataKuliah->id) }}">
                                    <button type="button" class="btn btn-sm btn-danger">
                                        <i class="fa fa-file-pdf"></i>
                                    </button>
                                </a>
                                <a class="btn btn-warning btn-sm" type="button"
                                    href="{{ route('mahasiswa.mata-kuliah.leaderboard', $mataKuliah->id) }}">
                                    <i class="bi bi-award-fill"></i>
                                </a>

                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
