document.addEventListener('DOMContentLoaded', () => {
    const notifications = document.querySelectorAll('.notification');

    // Apparition des notifications
    notifications.forEach(notification => {
        setTimeout(() => {
            notification.classList.add('show'); // Ajout de la classe pour l'effet d'apparition
        }, 100);
    });

    // Disparition automatique après 4 secondes
    setTimeout(() => {
        notifications.forEach(notification => {
            notification.classList.add('fade-out'); // Ajout de la classe pour l'effet de disparition
            setTimeout(() => {
                notification.remove(); // Suppression du DOM après disparition
            }, 500); // Durée de la transition CSS
        });
    }, 4000); // Temps avant la disparition
});

function addNotification(type, message) {
    const notificationContainer = document.getElementById('notifications');
    if (!notificationContainer) return;

    const notification = document.createElement('div');
    notification.className = `notification is-${type} show`;
    notification.textContent = message;

    // Ajout de la notification au conteneur
    notificationContainer.appendChild(notification);

    // Supprime la notification après 5 secondes
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 500);
    }, 5000);
}