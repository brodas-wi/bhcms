export const editorConfig = {
    container: '#gjs',
    height: '100%',
    width: 'auto',
    storageManager: false,
    plugins: ['gjs-blocks-basic'],
    pluginsOpts: {
        'gjs-blocks-basic': {
            blocks: [
                'column1', 'column2', 'column3', 'column3-7',
                'text', 'link', 'image', 'video', 'map'
            ],
            flexGrid: 1,
            stylePrefix: 'gjs-',
            category: 'Básico'
        },
    },
    canvas: {
        styles: [
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'
        ],
        scripts: [
            'https://code.jquery.com/jquery-3.5.1.slim.min.js',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'
        ]
    },
    deviceManager: {
        devices: [
            {
                name: 'Escritorio',
                width: ''
            },
            {
                name: 'Tablet',
                width: '768px'
            },
            {
                name: 'Móvil',
                width: '320px'
            }
        ]
    },
    layerManager: {
        appendTo: '#layers-container'
    },
    styleManager: {
        appendTo: '#styles-container'
    },
    traitManager: {
        appendTo: '#traits-container'
    }
};
