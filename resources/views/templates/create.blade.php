<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Creador de Plantillas</title>
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .container {
                margin-top: 20px;
            }

            #preview {
                border: 2px solid #ccc;
                padding: 20px;
                margin-top: 20px;
                min-height: 400px;
            }

            .preview-section {
                border: 1px dashed #999;
                padding: 10px;
                margin: 5px 0;
                min-height: 50px;
            }

            .editable {
                border: 1px solid #ccc;
                padding: 10px;
                margin: 5px 0;
                min-height: 100px;
            }

            #style-editor {
                position: fixed;
                top: 20px;
                right: 20px;
                background: #f4f4f4;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .ql-toolbar {
                background: #fff;
            }

            /* Diseño responsivo */
            @media (max-width: 767px) {
                #style-editor {
                    width: 100%;
                    position: relative;
                    top: auto;
                    right: auto;
                    box-shadow: none;
                }
            }

            /* Estilos de navegación */
            .navbar-nav {
                margin-top: 10px;
            }

            .navbar-nav a {
                margin-right: 15px;
            }

            .navbar-nav a:hover {
                text-decoration: underline;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>Editor</h2>
                    <div id="editor-container">
                        <div id="header-editor" class="editable">
                            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                                <a class="navbar-brand" href="#">Navbar</a>
                                <button class="navbar-toggler" type="button" data-toggle="collapse"
                                    data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                                    aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="navbar-collapse collapse" id="navbarNav">
                                    <ul class="navbar-nav">
                                        <li class="nav-item active">
                                            <a class="nav-link" href="#">Home <span
                                                    class="sr-only">(current)</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Features</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Pricing</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link disabled" href="#">Disabled</a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <div id="body-editor" class="editable">
                            <div class="jumbotron">
                                <h1 class="display-4">Hello, world!</h1>
                                <p class="lead">This is a simple hero unit, a simple jumbotron-style component for
                                    calling extra attention to featured content or information.</p>
                                <hr class="my-4">
                                <p>It uses utility classes for typography and spacing to space content out within the
                                    larger container.</p>
                                <p class="lead">
                                    <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
                                </p>
                            </div>
                        </div>
                        <div id="footer-editor" class="editable">
                            <footer class="my-md-5 pt-md-5 border-top pt-4">
                                <div class="row">
                                    <div class="col-6 col-md">
                                        <h5>Features</h5>
                                        <ul class="list-unstyled text-small">
                                            <li><a class="text-muted" href="#">Cool stuff</a></li>
                                            <li><a class="text-muted" href="#">Random feature</a></li>
                                            <li><a class="text-muted" href="#">Team feature</a></li>
                                            <li><a class="text-muted" href="#">Stuff for developers</a></li>
                                            <li><a class="text-muted" href="#">Another one</a></li>
                                            <li><a class="text-muted" href="#">Last time</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-6 col-md">
                                        <h5>Resources</h5>
                                        <ul class="list-unstyled text-small">
                                            <li><a class="text-muted" href="#">Resource</a></li>
                                            <li><a class="text-muted" href="#">Resource name</a></li>
                                            <li><a class="text-muted" href="#">Another resource</a></li>
                                            <li><a class="text-muted" href="#">Final resource</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-6 col-md">
                                        <h5>About</h5>
                                        <ul class="list-unstyled text-small">
                                            <li><a class="text-muted" href="#">Team</a></li>
                                            <li><a class="text-muted" href="#">Locations</a></li>
                                            <li><a class="text-muted" href="#">Privacy</a></li>
                                            <li><a class="text-muted" href="#">Terms</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <h2>Previsualización</h2>
                    <div id="preview">
                        <div id="header" class="preview-section">
                            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                                <a class="navbar-brand" href="#">Navbar</a>
                                <button class="navbar-toggler" type="button" data-toggle="collapse"
                                    data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                                    aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="navbar-collapse collapse" id="navbarNav">
                                    <ul class="navbar-nav">
                                        <li class="nav-item active">
                                            <a class="nav-link" href="#">Home <span
                                                    class="sr-only">(current)</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Features</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Pricing</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link disabled" href="#">Disabled</a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <div id="body" class="preview-section">
                            <div class="jumbotron">
                                <h1 class="display-4">Hello, world!</h1>
                                <p class="lead">This is a simple hero unit, a simple jumbotron-style component for
                                    calling extra attention to featured content or information.</p>
                                <hr class="my-4">
                                <p>It uses utility classes for typography and spacing to space content out within the
                                    larger container.</p>
                                <p class="lead">
                                    <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
                                </p>
                            </div>
                        </div>
                        <div id="footer" class="preview-section">
                            <footer class="my-md-5 pt-md-5 border-top pt-4">
                                <div class="row">
                                    <div class="col-6 col-md">
                                        <h5>Features</h5>
                                        <ul class="list-unstyled text-small">
                                            <li><a class="text-muted" href="#">Cool stuff</a></li>
                                            <li><a class="text-muted" href="#">Random feature</a></li>
                                            <li><a class="text-muted" href="#">Team feature</a></li>
                                            <li><a class="text-muted" href="#">Stuff for developers</a></li>
                                            <li><a class="text-muted" href="#">Another one</a></li>
                                            <li><a class="text-muted" href="#">Last time</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-6 col-md">
                                        <h5>Resources</h5>
                                        <ul class="list-unstyled text-small">
                                            <li><a class="text-muted" href="#">Resource</a></li>
                                            <li><a class="text-muted" href="#">Resource name</a></li>
                                            <li><a class="text-muted" href="#">Another resource</a></li>
                                            <li><a class="text-muted" href="#">Final resource</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-6 col-md">
                                        <h5>About</h5>
                                        <ul class="list-unstyled text-small">
                                            <li><a class="text-muted" href="#">Team</a></li>
                                            <li><a class="text-muted" href="#">Locations</a></li>
                                            <li><a class="text-muted" href="#">Privacy</a></li>
                                            <li><a class="text-muted" href="#">Terms</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="style-editor">
            <h3>Editor de Estilos</h3>
            <select id="element-selector">
                <option value="header">Header</option>
                <option value="body">Body</option>
                <option value="footer">Footer</option>
                <option value="navbar">Navegación</option>
            </select>
            <div>
                <label for="background-color">Color de fondo:</label>
                <input type="text" id="background-color" class="color-picker">
            </div>
            <div>
                <label for="text-color">Color de texto:</label>
                <input type="text" id="text-color" class="color-picker">
            </div>
            <div>
                <label for="font-size">Tamaño de fuente:</label>
                <input type="range" id="font-size" min="8" max="72" value="16">
                <span id="font-size-value">16px</span>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const preview = document.getElementById('preview');
                const elementSelector = document.getElementById('element-selector');
                const fontSizeSlider = document.getElementById('font-size');
                const fontSizeValue = document.getElementById('font-size-value');
                const backgroundColorPicker = document.getElementById('background-color');
                const textColorPicker = document.getElementById('text-color');

                // Inicializar Quill para cada elemento editable con todas las herramientas
                const editors = {};
                const toolbarOptions = [
                    ['bold', 'italic', 'underline', 'strike'], // toggled buttons
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video', 'formula'],

                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }], // custom button values
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }, {
                        'list': 'check'
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }], // superscript/subscript
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }], // outdent/indent
                    [{
                        'direction': 'rtl'
                    }], // text direction

                    [{
                        'size': ['small', false, 'large', 'huge']
                    }], // custom dropdown
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],

                    [{
                        'color': []
                    }, {
                        'background': []
                    }], // dropdown with defaults from theme
                    [{
                        'font': []
                    }],
                    [{
                        'align': []
                    }],

                    ['clean'] // remove formatting button
                ];
                document.querySelectorAll('.editable').forEach(el => {
                    const editorId = el.id.replace('-editor', '');
                    editors[editorId] = new Quill(el, {
                        theme: 'snow',
                        modules: {
                            toolbar: toolbarOptions
                        }
                    });

                    // Actualizar previsualización cuando se edita el contenido
                    editors[editorId].on('text-change', function() {
                        const previewElement = document.getElementById(editorId);
                        if (previewElement) {
                            previewElement.innerHTML = editors[editorId].root.innerHTML;
                        }
                    });
                });

                // Inicializar color pickers
                $(backgroundColorPicker).spectrum({
                    showInput: true,
                    preferredFormat: "hex",
                    change: updateStyles
                });

                $(textColorPicker).spectrum({
                    showInput: true,
                    preferredFormat: "hex",
                    change: updateStyles
                });

                // Actualizar estilos cuando se cambia el tamaño de fuente
                fontSizeSlider.addEventListener('input', function() {
                    fontSizeValue.textContent = this.value + 'px';
                    updateStyles();
                });

                // Función para actualizar estilos
                function updateStyles() {
                    const selectedElement = document.getElementById(elementSelector.value);
                    if (selectedElement) {
                        const backgroundColor = $(backgroundColorPicker).spectrum('get').toHexString();
                        const textColor = $(textColorPicker).spectrum('get').toHexString();
                        const fontSize = fontSizeSlider.value + 'px';

                        selectedElement.style.backgroundColor = backgroundColor;
                        selectedElement.style.color = textColor;
                        selectedElement.style.fontSize = fontSize;

                        if (elementSelector.value === 'navbar') {
                            const navLinks = selectedElement.querySelectorAll('.nav-link');
                            navLinks.forEach(link => {
                                link.style.color = textColor;
                                link.style.fontSize = fontSize;
                            });
                        }
                    }
                }

                // Permitir arrastrar y soltar elementos en la previsualización
                new Sortable(preview, {
                    animation: 150,
                    ghostClass: 'blue-background-class'
                });

                // Actualizar estilos iniciales
                updateStyles();
            });
        </script>
    </body>

</html>
