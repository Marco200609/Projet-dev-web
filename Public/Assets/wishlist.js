document.querySelectorAll('.wishlist-checkbox').forEach((checkbox) => {
    checkbox.addEventListener('change', function() {
        let offreId = this.dataset.offreId;
        let checked = this.checked;
        fetch('/changeWishlist', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_offre=' + encodeURIComponent(offreId)
        })
            .then(response => response.json())
            .then(data => {
                let heartIcon = this.nextElementSibling.querySelector('.heart-icon');
                if (data.success) {
                    if (data.present) {
                        heartIcon.classList.add('bi-heart-fill');
                        heartIcon.classList.remove('bi-heart');
                    } else {
                        heartIcon.classList.remove('bi-heart-fill');
                        heartIcon.classList.add('bi-heart');
                    }
                } else {
                    this.checked = !checked;
                    alert('Erreur lors de la mise à jour de la wishlist.');
                }
            });
    });
});
