@extends('layouts.main')
@section('_title', 'Virtual Lab Api Tester')
@section('page-heading', 'Api Tester')
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
            <iframe src="{{ route('vlab.api-tester') }}" class="load_iframe">
            </iframe>
        </div>
    </div>
</section>
@endsection