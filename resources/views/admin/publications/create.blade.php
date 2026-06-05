@extends('layouts.admin')

@section('title', 'Nouvelle publication')

@section('content')
    @include('admin.publications._form', ['publication' => null])
@endsection
