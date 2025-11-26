@extends('errors.layout')

@section('title', 'Error del Servidor')
@section('code', '500')
@section('message', 'Algo salió mal')

@section('icon')
    <i class="fas fa-cogs text-5xl text-gray-600"></i>
@endsection

@section('description')
    Nuestros servidores encontraron un problema inesperado. El equipo técnico ya ha sido notificado. Por favor intenta más tarde.
@endsection
