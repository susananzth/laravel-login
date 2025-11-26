@extends('errors.layout')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('Service Unavailable'))

@section('icon')
    <i class="fas fa-cogs text-5xl text-gray-600"></i>
@endsection

@section('description')
    Nuestros servidores encontraron un problema inesperado. El equipo técnico ya ha sido notificado. Por favor intenta más tarde.
@endsection
