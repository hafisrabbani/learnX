@extends('layouts.main')
@section('_title', 'Virtual Lab Geometric Optics')
@section('page-heading', 'Geometric Optics')
@section('css')
<style>
    .load_iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .card {
        height: 80vh;
    }
</style>
@endsection
@section('page-content')
<section class="row">
    <div class="col-12">
        <div class="card">
            <iframe src="https://phet.colorado.edu/sims/html/geometric-optics/latest/geometric-optics_en.html" class="load_iframe">
            </iframe>
        </div>
    </div>
</section>
@endsection
@section('js')