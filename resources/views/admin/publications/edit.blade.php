@extends('layouts.admin')

@section('title', 'Modifier — '.$publication->title)

@section('content')
    @include('admin.publications._form', ['publication' => $publication])
@endsection
