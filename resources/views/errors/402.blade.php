@extends('errors.layout')

@section('title', __('Payment Required'))
@section('code', '402')
@section('message', __('Payment Required'))

@section('icon')
    <i class="fas fa-lock text-5xl text-moto-red"></i>
@endsection

@section('description')
    Lo sentimos, el pago es requerido.
@endsection
