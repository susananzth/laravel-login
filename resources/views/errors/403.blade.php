@extends('errors.layout')

@section('title', 'Acceso Denegado')
@section('code', '403')
@section('message', 'Acceso Restringido')

@section('icon')
    <i class="fas fa-user-lock text-5xl text-moto-red"></i>
@endsection

@section('description')
    Lo sentimos, no tienes los permisos necesarios para acceder a esta sección. Si crees que es un error, contacta al administrador.
@endsection
