<!-- resources/views/plugins/preview.blade.php -->
@extends('layouts.app')

@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="mb-4">
            <h2>Vista previa: {{ $plugin->original_name }} - {{ $viewName }}</h2>
            <a href="{{ route('plugin.index') }}" class="btn btn-secondary">Volver</a>
        </div>

        <div class="card">
            <div class="card-body">
                {!! $content !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
