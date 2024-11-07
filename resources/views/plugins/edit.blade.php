@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Editar Plugin: {{ $plugin->original_name }}</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('plugins.update', $plugin) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $plugin->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="original_name" class="form-label">Nombre Original</label>
                <input type="text" class="form-control" id="original_name" name="original_name"
                    value="{{ old('original_name', $plugin->original_name) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $plugin->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="version" class="form-label">Versión</label>
                <input type="text" class="form-control" id="version" name="version"
                    value="{{ old('version', $plugin->version) }}" disabled>
            </div>

            <div class="mb-3">
                <label for="author" class="form-label">Autor</label>
                <input type="text" class="form-control" id="author" name="author"
                    value="{{ old('author', $plugin->author) }}" disabled>
            </div>

            <div class="mb-3">
                <label for="main_class" class="form-label">Clase Principal</label>
                <input type="text" class="form-control" id="main_class" value="{{ $plugin->main_class }}" disabled>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                    {{ old('is_active', $plugin->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Activo</label>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Plugin</button>
            <a href="{{ route('plugin.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        /* Puedes agregar estilos personalizados aquí si es necesario */
    </style>
@endpush

@push('scripts')
    <script>
        // Puedes agregar JavaScript personalizado aquí si es necesario
    </script>
@endpush
