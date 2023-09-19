@extends('layouts.main')
@section('_title', 'Virtual Lab Circuit')
@section('page-heading', 'Virtual Lab Circuit Simulation')
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
            <iframe src="https://phet.colorado.edu/sims/html/circuit-construction-kit-ac/latest/circuit-construction-kit-ac_en.html" class="load_iframe">
            </iframe>
        </div>
    </div>
</section>
@endsection
@section('js')