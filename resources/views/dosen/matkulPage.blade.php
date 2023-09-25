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
                            <th scope="col">Kode MK</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mataKuliahs as $mataKuliah)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$mataKuliah->nama_mk}}</td>
                            <td>{{$mataKuliah->kode_mk}}</td>
                            <td>
                                <a href="{{route('dosen.tugas.index', $mataKuliah->id)}}">
                                    <button type="button" class="btn btn-sm btn-primary"><i class="fas fa-file"></i>
                                    </button>
                                </a>
                                <a href="{{route('dosen.materi.index', $mataKuliah->id)}}">
                                    <button type="button" class="btn btn-sm btn-danger text-white">
                                        <i class="fas fa-book text-white"></i>
                                </a>
                                <a href="{{ env('CONFERENCE_URL') }}/join/room-{{ base64_encode($mataKuliah->id) }}">
                                    <button type="button" class="btn btn-sm btn-success">
                                        <i class="fa fa-video"></i>
                                    </button>
                                </a>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
</section>
@endsection
