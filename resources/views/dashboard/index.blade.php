@extends('layouts.main')

@section('page-heading','Dashboard')
@section('_title','Dashboard')
@section('page-content')
@if(auth()->user()->role == 'mahasiswa')
@include('dashboard.mhs')
@elseif(auth()->user()->role == 'dosen')
@include('dashboard.dosen')
@elseif(auth()->user()->role == 'admin')
@include('dashboard.admin')
@endif
@endsection