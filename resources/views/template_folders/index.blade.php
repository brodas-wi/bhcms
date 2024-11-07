@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h3 class="card-header bg-light">{{ __('Gestión de carpetas') }}</h3>

                    <div class="card-body">

                        <form action="{{ route('template_folders.store') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="input-group">
                                <div class="col-auto my-md-0 my-1">
                                    <input type="text" name="name" class="form-control" placeholder="New Folder Name"
                                        required>
                                </div>
                                <div class="input-group-append my-md-0 my-1 mx-md-1 mx-0">
                                    <button type="submit" class="btn btn-primary">Create Folder</button>
                                </div>
                            </div>
                        </form>

                        <div class="list-group">
                            @foreach ($folders as $folder)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5>{{ $folder->name }}</h5>
                                        <div>
                                            <button class="btn btn-sm btn-primary" data-toggle="collapse"
                                                data-target="#folder{{ $folder->id }}">
                                                Show Templates ({{ $folder->templates->count() }})
                                            </button>
                                            <form action="{{ route('template_folders.destroy', $folder) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="collapse mt-3" id="folder{{ $folder->id }}">
                                        <div class="list-group">
                                            @foreach ($folder->templates as $template)
                                                <a href="{{ route('templates.edit', $template) }}"
                                                    class="list-group-item list-group-item-action">
                                                    {{ $template->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
