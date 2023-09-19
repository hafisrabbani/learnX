@extends('layouts.main')
@section('_title', 'Virtual Lab Operating System')
@section('page-heading', 'Ubuntu Terminal')
@section('css')
<style>
    .load_iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .card {
        height: 90vh;
    }
</style>
@endsection
@section('page-content')
<section class="row">
    <div class="col-12">
        <div class="card">
            <iframe src="http://127.0.0.1:8000/utils/vlab/terminal" class="load_iframe">
            </iframe>
        </div>
    </div>
</section>
@endsection
@section('js')