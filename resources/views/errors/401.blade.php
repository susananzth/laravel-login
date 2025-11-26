@extends('errors.layout')

@section('title', 'No autorizado')
@section('code', '401')
@section('message', 'No autorizado')

@section('icon')
    <i class="fas fa-user-lock text-5xl text-moto-red"></i>
@endsection

@section('description')
    Lo sentimos, debe iniciar sesión para acceder al conenido.
@endsection
