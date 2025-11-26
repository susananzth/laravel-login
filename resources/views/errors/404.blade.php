@extends('errors.layout')

@section('title', 'Página no encontrada')
@section('code', '404')
@section('message', '¿Te has perdido?')

@section('icon')
    <i class="fas fa-map-signs text-5xl text-blue-500"></i>
@endsection

@section('description')
    La página que buscas no existe, ha sido movida o eliminada. Verifica la URL e intenta nuevamente.
@endsection
