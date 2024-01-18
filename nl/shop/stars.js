document.addEventListener("DOMContentLoaded", function () {
    const stars = document.querySelectorAll('.star');
    const selectedRating = document.getElementById('selected-rating');

    stars.forEach(star => {
        star.addEventListener('mouseover', function () {
            const rating = this.getAttribute('data-rating');
            highlightStars(rating);
        });

        star.addEventListener('mouseout', function () {
            const rating = selectedRating.value;
            highlightStars(rating);
        });

        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-rating');
            selectedRating.value = rating;
        });
    });

    function highlightStars(rating) {
        stars.forEach((star, index) => {
            star.classList.remove('active');
            if (index < rating) {
                star.classList.add('active');
            }
        });
    }
});