
    const addImageBtn = document.getElementById('add-image-btn');
    const imageInput = document.getElementById('image-input');
    const thumbnails = document.getElementById('thumbnails');
    const mainImage = document.getElementById('main-image').querySelector('img');

    addImageBtn.addEventListener('click', () => {
        imageInput.click();
    });

    imageInput.addEventListener('change', () => {
        const file = imageInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            // Créer une miniature
            const thumbnailContainer = document.createElement('div');
            thumbnailContainer.classList.add('thumbnail');

            const img = document.createElement('img');
            img.src = e.target.result;

            // Ajouter un événement pour changer l'image principale
            img.addEventListener('click', () => {
                mainImage.src = e.target.result;
            });

            // Bouton de suppression
            const deleteBtn = document.createElement('button');
            deleteBtn.textContent = "×";
            deleteBtn.classList.add('delete-btn');
            deleteBtn.addEventListener('click', () => {
                thumbnails.removeChild(thumbnailContainer);
                if (thumbnails.children.length > 0) {
                    mainImage.src = thumbnails.children[0].querySelector('img').src;
                } else {
                    mainImage.src = "https://via.placeholder.com/300x300?text=Aperçu";
                }
            });

            thumbnailContainer.appendChild(img);
            thumbnailContainer.appendChild(deleteBtn);
            thumbnails.appendChild(thumbnailContainer);

            // Mettre à jour l'image principale
            mainImage.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });