@extends('adminlte::page')

@section('title', 'Edit Diskon')

@section('content_header')
    <h1>Edit Data Diskon</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.diskon.update', $diskon->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('diskon._form')
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.diskon.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop
