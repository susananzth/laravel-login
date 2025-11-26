@extends('errors.layout')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('Too Many Requests'))

@section('icon')
    <i class="fas fa-exclamation text-5xl text-yellow-500"></i>
@endsection

@section('description')
    Nuestros servidores encontraron un problema inesperado. El equipo técnico ya ha sido notificado. Por favor intenta más tarde.
@endsection
