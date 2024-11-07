@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h3 class="card-header bg-light">{{ __('Crear nuevo permiso') }}</h3>

                    <div class="card-body">

                        <!-- Formulario para crear un nuevo permiso -->
                        <form action="{{ route('permissions.store') }}" method="POST">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Nombre</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0 mt-2">
                                <div class="col-md-12 d-flex justify-content-between flex-row py-2">
                                    <div class="my-1">
                                        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Regresar</a>
                                    </div>

                                    <div class="my-1">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Crear Permiso') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
