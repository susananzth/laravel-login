@extends('errors.layout')

@section('title', 'Sesión Expirada')
@section('code', '419')
@section('message', 'La página ha expirado')

@section('icon')
    <i class="fas fa-hourglass-end text-5xl text-yellow-500"></i>
@endsection

@section('description')
    Tu sesión ha caducado por inactividad. Por favor, recarga la página e intenta de nuevo.
@endsection
