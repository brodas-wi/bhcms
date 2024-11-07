export class EditorNotifications {
    constructor() {
        this.initializeContainer();
    }

    initializeContainer() {
        // Create container for notifications if it doesn't exist
        if (!document.getElementById('editor-notifications')) {
            const container = document.createElement('div');
            container.id = 'editor-notifications';
            container.className = 'editor-notifications';
            document.body.appendChild(container);
        }
    }

    show(message, type = 'info', duration = 3000) {
        const container = document.getElementById('editor-notifications');
        const notification = this.createNotification(message, type);

        container.appendChild(notification);
        this.animateIn(notification);

        // Set timeout for removal
        setTimeout(() => {
            this.animateOut(notification).then(() => {
                notification.remove();
            });
        }, duration);
    }

    createNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `editor-notification ${type}`;

        // Add icon based on type
        const icon = this.getIconForType(type);

        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${icon}"></i>
                <span>${message}</span>
            </div>
            <button class="notification-close">
                <i class="fas fa-times"></i>
            </button>
        `;

        // Add click listener to close button
        notification.querySelector('.notification-close').addEventListener('click', () => {
            this.animateOut(notification).then(() => {
                notification.remove();
            });
        });

        return notification;
    }

    getIconForType(type) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        return icons[type] || icons.info;
    }

    async animateIn(element) {
        element.style.opacity = '0';
        element.style.transform = 'translateX(100%)';

        // Trigger reflow
        element.offsetHeight;

        element.style.transition = 'all 0.3s ease-out';
        element.style.opacity = '1';
        element.style.transform = 'translateX(0)';

        return new Promise(resolve => {
            setTimeout(resolve, 300);
        });
    }

    async animateOut(element) {
        element.style.opacity = '0';
        element.style.transform = 'translateX(100%)';

        return new Promise(resolve => {
            setTimeout(resolve, 300);
        });
    }

    // Utility methods for common notifications
    success(message) {
        this.show(message, 'success');
    }

    error(message) {
        this.show(message, 'error', 5000);
    }

    warning(message) {
        this.show(message, 'warning', 4000);
    }

    info(message) {
        this.show(message, 'info');
    }
}

// Styles for notifications
const style = document.createElement('style');
style.textContent = `
    .editor-notifications {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .editor-notification {
        min-width: 300px;
        padding: 12px 20px;
        border-radius: 4px;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .editor-notification.success {
        background: #d4edda;
        border-left: 4px solid #28a745;
        color: #155724;
    }

    .editor-notification.error {
        background: #f8d7da;
        border-left: 4px solid #dc3545;
        color: #721c24;
    }

    .editor-notification.warning {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        color: #856404;
    }

    .editor-notification.info {
        background: #d1ecf1;
        border-left: 4px solid #17a2b8;
        color: #0c5460;
    }

    .notification-close {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.2s;
    }

    .notification-close:hover {
        opacity: 1;
    }
`;

document.head.appendChild(style);

// Export singleton instance
export const notifications = new EditorNotifications();
