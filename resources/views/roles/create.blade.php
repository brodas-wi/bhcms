@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h3 class="card-header bg-light">{{ __('Crear nuevo rol') }}</h3>

                    <div class="card-body">
                        <form method="POST" action="{{ route('roles.store') }}">
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

                            {{-- Button form group --}}
                            <div class="form-group row mb-0 mt-2">
                                <div class="col-md-12 d-flex justify-content-between flex-row py-2">
                                    <div class="my-1">
                                        <a href="{{ route('roles.index') }}" class="btn btn-warning">
                                            {{ __('Regresar') }}
                                        </a>
                                    </div>

                                    <div class="my-1">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Crear Rol') }}
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
