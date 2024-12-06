document.addEventListener('DOMContentLoaded', () => {
    const notifications = document.querySelectorAll('.notification');

    // Fonction pour déclencher la disparition d'une notification
    function fadeOutNotification(notification) {
        // Vérifie si la notification n'est pas déjà en train de disparaître
        if (!notification.classList.contains('fade-out')) {
            notification.classList.add('fade-out'); // Début de la transition CSS de disparition
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove(); // Suppression du DOM après disparition
                }
            }, 500); // Durée de la transition CSS
        }
    }

    // Apparition des notifications
    notifications.forEach(notification => {
        setTimeout(() => {
            notification.classList.add('show'); // Ajout de la classe pour l'effet d'apparition
        }, 100);

        // Ajout d'un évènement au clic pour les faire disparaître immédiatement
        notification.addEventListener('click', () => {
            fadeOutNotification(notification);
        });
    });

    // Disparition automatique après 5 secondes si non cliquées
    setTimeout(() => {
        notifications.forEach(notification => {
            fadeOutNotification(notification);
        });
    }, 5000);
});