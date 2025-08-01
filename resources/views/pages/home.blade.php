@extends('layouts.app')

@section('title', 'Beranda - Top Up Game')

@section('content')
    @include('components.banner')
    @include('components.flash-sale')
    @include('components.game-list')
@endsection
