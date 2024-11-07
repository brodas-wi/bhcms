@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Crear Nuevo Plugin</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('plugin.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre del plugin</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="formatted_name" class="form-label">Nombre formateado</label>
                <input type="text" class="form-control" id="formatted_name" name="formatted_name" disabled readonly>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="is_global" class="form-label">Plugin Global</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_global" name="is_global" value="1"
                        {{ old('is_global') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_global">
                        Activar como plugin global
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <label for="version" class="form-label">Versión</label>
                <input type="text" class="form-control" id="version" name="version" value="1.0.0" disabled readonly>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Autor</label>
                <input type="text" class="form-control" id="author" name="author" value="{{ Auth::user()->name }}"
                    disabled readonly>
            </div>
            <div class="mb-3">
                <label for="main_class" class="form-label">Nombre de la Clase Principal (Main Class)</label>
                <input type="text" class="form-control" id="main_class" name="main_class" value="{{ old('main_class') }}"
                    required>
                <small class="form-text text-muted">Este debe ser el nombre de la clase principal de su plugin, e.j.,
                    MiPlugin</small>
            </div>
            <div class="mb-3">
                <label for="hooks" class="form-label">Hooks disponibles</label>
                <div class="row">
                    @foreach ($availableHooks as $hook => $description)
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="hook_{{ $hook }}"
                                    name="selected_hooks[]" value="{{ $hook }}"
                                    {{ in_array($hook, old('selected_hooks', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="hook_{{ $hook }}">
                                    {{ $hook }}
                                    <small class="d-block text-muted">{{ $description }}</small>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <small class="form-text text-muted">Selecciona todos los hooks que necesites para la ejecución del
                    plugin</small>
            </div>
            <div class="mb-3">
                <label for="config" class="form-label">Configuración (JSON)</label>
                <div id="config-editor" style="height: 200px;"></div>
                <input type="hidden" name="config" id="config-input">
                <small class="form-text text-muted">Introduzca la configuración del plugin como JSON, e.j., {"key":
                    "value"}</small>
            </div>
            <div class="mb-3">
                <label for="migrations" class="form-label">Migraciones (una por linea)</label>
                <textarea class="form-control" id="migrations" name="migrations" rows="3">{{ old('migrations') }}</textarea>
                <small class="form-text text-muted">Introduzca cada nombre de migración por linea, e.j.,
                    "create_plugin_table"</small>
            </div>
            <div class="mb-3">
                <label for="create_model" class="form-label">Crear Modelo</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="create_model" name="create_model"
                        value="1" {{ old('create_model') ? 'checked' : '' }}>
                    <label class="form-check-label" for="create_model">
                        Crear archivo de modelo
                    </label>
                </div>
                <input type="text" class="form-control mt-2" id="model_name" name="model_name"
                    value="{{ old('model_name') }}" placeholder="Nombre del modelo (ej. User)">
            </div>
            <div class="mb-3">
                <label for="create_controller" class="form-label">Crear Controlador</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="create_controller" name="create_controller"
                        value="1" {{ old('create_controller') ? 'checked' : '' }}>
                    <label class="form-check-label" for="create_controller">
                        Crear archivo de controlador
                    </label>
                </div>
                <input type="text" class="form-control mt-2" id="controller_name" name="controller_name"
                    value="{{ old('controller_name') }}" placeholder="Nombre del controlador (ej. UserController)">
            </div>
            <div class="mb-3">
                <label for="views" class="form-label">Vistas (una por linea)</label>
                <textarea class="form-control" id="views" name="views" rows="3">{{ old('views') }}</textarea>
                <small class="form-text text-muted">Introduzca el nombre de cada vista en una nueva línea, e.j.,
                    "dashboard"</small>
            </div>
            <button type="submit" class="btn btn-primary">Crear Plugin</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const nameInput = document.getElementById('name');
            const formattedNameInput = document.getElementById('formatted_name');
            const mainClassInput = document.getElementById('main_class');
            const createModelCheckbox = document.getElementById('create_model');
            const modelNameInput = document.getElementById('model_name');
            const createControllerCheckbox = document.getElementById('create_controller');
            const controllerNameInput = document.getElementById('controller_name');

            // Inicializar campos con valores por defecto
            const versionInput = document.getElementById('version');
            versionInput.value = "1.0.0";
            const authorInput = document.getElementById('author');
            authorInput.value = @json($userName);

            function formatName(name) {
                return name
                    .toLowerCase()
                    .replace(/[áàäâã]/g, 'a')
                    .replace(/[éèëê]/g, 'e')
                    .replace(/[íìïî]/g, 'i')
                    .replace(/[óòöôõ]/g, 'o')
                    .replace(/[úùüû]/g, 'u')
                    .replace(/ñ/g, 'n')
                    .replace(/[^a-z0-9]/g, ' ')
                    .split(' ')
                    .filter(word => word.length > 0)
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join('');
            }

            nameInput.addEventListener('input', function() {
                const formattedName = formatName(this.value);
                formattedNameInput.value = formattedName;
                mainClassInput.value = formattedName;
                modelNameInput.value = formattedName;
                controllerNameInput.value = formattedName + 'Controller';
            });

            createModelCheckbox.addEventListener('change', function() {
                modelNameInput.disabled = !this.checked;
            });

            createControllerCheckbox.addEventListener('change', function() {
                controllerNameInput.disabled = !this.checked;
            });

            // Configurar editor Ace para JSON
            const configEditor = ace.edit("config-editor");
            configEditor.setTheme("ace/theme/monokai");
            configEditor.session.setMode("ace/mode/json");

            // Manejar la validación del formulario
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                try {
                    const configJson = configEditor.getValue();
                    JSON.parse(configJson);
                    document.getElementById('config-input').value = configJson;
                    form.submit();
                } catch (error) {
                    alert('La configuración debe ser un JSON válido. Por favor, revise su entrada.');
                }
            });
        });
    </script>
@endpush
