@extends('layouts.main')
@section('_title', 'Virtual Lab')
@section('page-heading', 'Virtual Lab')
@section('css')
<style>
    .card {
        height: 200px;
    }
</style>
@endsection
@section('page-content')
<section class="row">
    @foreach($virtualLabs as $key => $val)
    <div class="col-md-4">
        <div class="card">
            <h5 class="card-header">{{ $key }}</h5>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <p class="card-text">{{ $val['title'] }}</p>
                    </div>
                    <div class="col-4">
                        <div class="bg-primary text-white text-center py-2" style="border-radius: 10px;">
                            <i class="{{ $val['icon'] }} fa-1x"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ $val['url'] }}" class="btn btn-success mt-3" style="width: 100%;">Buka</a>
            </div>
        </div>
    </div>
    @endforeach

</section>
@endsection
@section('js')