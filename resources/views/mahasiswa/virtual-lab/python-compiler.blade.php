@extends('layouts.main')
@section('_title', 'Virtual Lab - Python Compiler')
@section('page-heading', 'Python Compiler')
@section('css')
<style>
    .load_iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .card {
        height: 120vh;
    }
</style>
@endsection
@section('page-content')
<section class="row">
    <div class="col-12">
        <div class="card">
            <iframe src="{{ route('vlab.python-compiler') }}" class="load_iframe">
            </iframe>
        </div>
    </div>
</section>
@endsection