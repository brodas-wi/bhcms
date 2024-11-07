@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Previsualización de Contenido</h2>
            <button class="btn btn-secondary" onclick="window.close()">
                Cerrar Previsualización
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="content-preview">
                    <h1 class="mb-4">{{ $content->title }}</h1>

                    <div class="metadata mb-4">
                        <span class="badge bg-primary">{{ ucfirst($content->type) }}</span>
                        @if ($content->categories->count() > 0)
                            @foreach ($content->categories as $category)
                                <span class="badge bg-secondary">{{ $category->name }}</span>
                            @endforeach
                        @endif
                        @if ($content->tags->count() > 0)
                            @foreach ($content->tags as $tag)
                                <span class="badge bg-info">{{ $tag->name }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="content-body">
                        {!! $content->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .content-preview {
            max-width: 800px;
            margin: 0 auto;
        }

        .metadata .badge {
            margin-right: 0.5rem;
        }

        .content-body {
            line-height: 1.6;
        }

        .content-body img {
            max-width: 100%;
            height: auto;
        }
    </style>
@endsection
