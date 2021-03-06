@extends('layouts.layout')
@section('content')
    <div id="designer-container"></div>
@endsection

@section('sidebar')
    @include('sidebars.default', ['sidebar'=> $sidebar_admin])
@endsection

@section('js')
    <script src="{{ mix('js/designer/main.js') }}"></script>
@endsection
