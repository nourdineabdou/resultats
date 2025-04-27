@extends('layout')

@section('module-css')
    <link href="{{ URL::asset('vendor/modules/admin/css/main.css') }}" rel="stylesheet">
@endsection

@section('module-js')
    <script src="{{ URL::asset('vendor/modules/admin/js/main.js') }}"></script>
@endsection
