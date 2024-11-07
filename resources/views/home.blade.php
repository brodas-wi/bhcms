@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h3 class="card-header bg-light">{{ __('Dashboard') }}</h3>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{ __('Ha iniciado sesion correctamente') }}

                        <div class="mt-3">
                            <!-- Mostrar el rol del usuario autenticado -->
                            <h3 class="my-1">{{ __('Bienvenido ') }}
                                <strong>{{ ucfirst(strtolower($user->name)) }}</strong></h3>
                            <p class="my-1">{{ __('Tu rol es: ') }}
                                <strong>
                                    @if (auth()->user()->getRoleNames()->isNotEmpty())
                                        {{ auth()->user()->getRoleNames()->implode(', ') }}
                                    @else
                                        Sin rol asignado
                                    @endif
                                </strong>
                            </p>
                        </div>

                        <!-- Botón para crear roles, solo visible para administradores -->
                        {{-- @if ($user->hasRole('admin') || $user->can('view_roles'))
                        <div class="mt-4">
                            <a href="{{ route('roles.index') }}" class="btn btn-primary">
                                Ver Roles
                            </a>
                        </div>
                    @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
