import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.star-rating .star');

    stars.forEach(star => {
        star.addEventListener('mouseover', () => {
            stars.forEach(s => s.style.color = '#ccc'); // Reset colors
            star.style.color = '#f5c518'; // Highlight hovered star
            let prev = star.previousElementSibling;
            while (prev) {
                prev.style.color = '#f5c518'; // Highlight previous stars
                prev = prev.previousElementSibling;
            }
        });

        star.addEventListener('mouseleave', () => {
            stars.forEach(s => {
                if (!s.previousElementSibling || !s.checked) {
                    s.style.color = '#ccc'; // Reset colors when not checked
                }
            });
        });
    });
});

