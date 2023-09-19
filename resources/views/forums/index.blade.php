@extends('layouts.main')

@section('page-heading','Forums')
@section('_title','Forums')
@section('css')
<style>
    .card-header {
        margin-bottom: -1.5rem;
    }
</style>
@endsection
@section('page-content')
<section class="row">
    <a href="{{ route('forums.create') }}" class="btn btn-primary m-3" style="width: 200px;"><i class="fas fa-plus"></i> Buat Pertanyaan</a>
    @foreach ($data as $item)
    <a href="{{ route('forums.show',$item->id) }}" class="text-decoration-none text-dark">
        <div class="col-12">
            <div class="card shadow">
                <div class="container">
                    <div class="card-header">
                        <h5 class="fw-bold">
                            {{ $item->judul }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted text-sm"><i class="fas fa-clock"></i>&nbsp;&nbsp;{{ $item->created_at->diffForHumans() }} | <i class="fas fa-user"></i> {{ $item->user->name }} | <i class="fas fa-comments"></i>&nbsp;&nbsp;{{ $item->threads->count() }} comments</p>
                        <p><span class="badge bg-primary text-white p-2">#{{ $item->matkul->nama_mk }}</span>&nbsp;<span class="badge bg-success text-white p-2">#{{ $item->materi->judul_materi }}</span></p>
                        <p class="text-muted">
                            {{ strip_tags(Str::limit($item->konten, 200))}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </a>
    @endforeach
</section>
@endsection