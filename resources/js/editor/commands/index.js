import { initializeSaveCommand } from './save';
import { initializeCodeEditorCommand } from './code-editor';
import { initializePanelCommands } from './panels';

export const initializeCommands = (editor) => {
    // Initialize all commands
    initializeSaveCommand(editor);
    initializeCodeEditorCommand(editor);
    initializePanelCommands(editor);

    // Initialize device preview commands
    initializeDeviceCommands(editor);

    // Initialize basic actions
    initializeBasicCommands(editor);
};

const initializeDeviceCommands = (editor) => {
    const devices = ['desktop', 'tablet', 'mobile'];
    devices.forEach(device => {
        editor.Commands.add(`set-device-${device}`, {
            run: editor => editor.setDevice(
                device === 'desktop' ? 'Escritorio' :
                    device === 'tablet' ? 'Tablet' : 'Móvil'
            )
        });
    });
};

const initializeBasicCommands = (editor) => {
    // Back to index command
    editor.Commands.add('back-to-index', {
        run: function (editor, sender) {
            const exitModal = new bootstrap.Modal(document.getElementById('exitConfirmModal'));
            exitModal.show();
        }
    });

    // Fullscreen command
    editor.Commands.add('fullscreen', {
        run: function (editor) {
            const el = editor.getContainer();
            if (el.requestFullscreen) el.requestFullscreen();
            else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
            else if (el.mozRequestFullScreen) el.mozRequestFullScreen();
            else if (el.msRequestFullscreen) el.msRequestFullscreen();
        }
    });

    // Component visibility command
    editor.Commands.add('sw-visibility', {
        run: function (editor) {
            const el = editor.getContainer();
            el.classList.toggle('show-borders');
        }
    });
};
