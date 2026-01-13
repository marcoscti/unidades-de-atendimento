
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.ua-filtros button').forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.dataset.filter;
            document.querySelectorAll('.ua-card').forEach(card => {
                if (filter === 'Todos' || card.dataset.tipo === filter) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
