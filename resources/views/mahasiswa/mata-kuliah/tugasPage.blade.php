@extends('layouts.main')

@section('_title', 'TUGAS')
@section('page-heading', 'TUGAS')

@section('page-content')
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    @foreach ($tugass as $tugas)
                    <div class="col-md-4">
                        <div class="card shadow p-1 mb-5 rounded" style="height: 15rem;">
                            <div class="card-body">

                                <div class="row align-items-center">
                                    <div class="col-10">
                                        <h5 class="fw-bold">{{ $tugas->judul_tugas }}</h5>
                                        <p class="text-muted">
                                            {{ str_word_count($tugas->deskripsi) > 10 ? substr($tugas->deskripsi, 0, 50)
                                            . '...' : $tugas->deskripsi }}
                                        </p>
                                        <p class="badge badge-primary bg-primary">
                                            {{ date('d/m/Y H:i:s', strtotime($tugas->deadline)) }}
                                        </p>
                                    </div>
                                    <div class="col-2">
                                        <div class="icon-container bg-{{ $randomIcon['color'] }} text-white float-end"
                                            style="width: 3rem; height: 3rem; border-radius: 5px; display: flex; justify-content: center; align-items: center;">
                                            <i class="{{ $randomIcon['icon'] }} icon" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('mahasiswa.tugas.getDetailTugas', $tugas->id) }}"
                                    class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
</section>
@endsection