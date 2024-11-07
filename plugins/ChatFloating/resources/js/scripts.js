// plugins/ChatFloating/resources/js/scripts.js
document.addEventListener('DOMContentLoaded', function () {
    // Obtener elementos
    var chatButton = document.getElementById('chat-button');
    var chatWindow = document.getElementById('chat-window');
    var closeButton = document.getElementById('close-chat');

    // Verificar que los elementos existan
    if (!chatButton || !chatWindow || !closeButton) {
        console.error('Chat elements not found');
        return;
    }

    // Mostrar/ocultar chat al hacer clic en el botón
    chatButton.addEventListener('click', function (e) {
        e.preventDefault();
        chatWindow.style.display = chatWindow.style.display === 'none' || !chatWindow.style.display ? 'block' : 'none';
    });

    // Cerrar chat
    closeButton.addEventListener('click', function (e) {
        e.preventDefault();
        chatWindow.style.display = 'none';
    });

    // Cerrar chat al hacer clic fuera
    document.addEventListener('click', function (e) {
        if (chatWindow.style.display === 'block' &&
            !chatWindow.contains(e.target) &&
            e.target !== chatButton) {
            chatWindow.style.display = 'none';
        }
    });
});
