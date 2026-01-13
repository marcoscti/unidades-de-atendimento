document.addEventListener('DOMContentLoaded', () => {
    const totalCountSpan = document.getElementById('ua-total-count');
    const cards = document.querySelectorAll('.ua-card');
    const filterButtons = document.querySelectorAll('.ua-filtros button');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.dataset.filter;
            let visibleCount = 0;

            cards.forEach(card => {
                const isVisible = (filter === 'Todos' || card.dataset.tipo === filter);

                card.style.display = isVisible ? 'flex' : 'none';

                if (isVisible) {
                    visibleCount++;
                }
            });

            if (totalCountSpan) {
                totalCountSpan.textContent = visibleCount;
            }
        });
    });
});