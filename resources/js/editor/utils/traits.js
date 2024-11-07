export const initializeTraits = (editor) => {
    // Setup default traits
    setupDefaultTraits(editor);

    // Setup trait events
    setupTraitEvents(editor);

    // Setup custom traits
    setupCustomTraits(editor);
};

const setupDefaultTraits = (editor) => {
    editor.DomComponents.getTypes().forEach(type => {
        const defaultTraits = type.model.prototype.defaults.traits || [];
        type.model.prototype.defaults.traits = [
            ...getCommonTraits(),
            ...defaultTraits
        ];
    });
};

const getCommonTraits = () => {
    return [
        {
            type: 'text',
            label: 'ID',
            name: 'id'
        },
        {
            type: 'text',
            label: 'Class',
            name: 'class'
        }
    ];
};

const setupTraitEvents = (editor) => {
    editor.on('component:selected', (component) => {
        updateComponentTraits(component);
    });

    editor.on('trait:change', (component, trait) => {
        handleTraitChange(component, trait);
    });
};

const updateComponentTraits = (component) => {
    const id = component.get('attributes').id || generateUniqueId(component);
    const classes = component.getClasses().join(' ');

    component.set('traits', [
        {
            type: 'text',
            label: 'ID',
            name: 'id',
            value: id
        },
        {
            type: 'text',
            label: 'Class',
            name: 'class',
            value: classes
        },
        ...component.get('traits').filter(trait =>
            trait.name !== 'id' && trait.name !== 'class'
        )
    ]);
};

const handleTraitChange = (component, trait) => {
    const traitName = trait.get('name');
    const value = trait.get('value');

    switch (traitName) {
        case 'id':
            component.addAttributes({ id: value });
            break;
        case 'class':
            component.setClass(value.split(' '));
            break;
        default:
            // Handle custom traits
            if (trait.get('changeProp')) {
                component.set(traitName, value);
            }
    }
};

const setupCustomTraits = (editor) => {
    // Add custom traits for specific components
    editor.DomComponents.addType('link', {
        model: {
            defaults: {
                traits: [
                    ...getCommonTraits(),
                    {
                        type: 'text',
                        label: 'Href',
                        name: 'href'
                    },
                    {
                        type: 'select',
                        label: 'Target',
                        name: 'target',
                        options: [
                            { value: '', name: 'This window' },
                            { value: '_blank', name: 'New window' }
                        ]
                    }
                ]
            }
        }
    });
};

const generateUniqueId = (component) => {
    return `${component.get('type')}-${Math.random().toString(36).substr(2, 9)}`;
};
