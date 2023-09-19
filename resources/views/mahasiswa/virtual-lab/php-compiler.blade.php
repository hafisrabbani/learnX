@extends('layouts.main')
@section('_title', 'Virtual Lab - PHP Compiler')
@section('page-heading', 'PHP Compiler')
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
            <iframe src="{{ route('vlab.compiler') }}" class="load_iframe">
            </iframe>
        </div>
    </div>
</section>
@endsection