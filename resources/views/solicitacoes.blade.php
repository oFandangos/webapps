@extends('laravel-usp-theme::master')
@section("content")
<div class="card">
    <div class="card-header"><h3><b>Minhas solicitações</b></h3></div>
    <div class="card-body">
        @include('partials.solicitacoes')
    </div>
</div>

@endsection