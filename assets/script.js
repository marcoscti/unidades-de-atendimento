document.addEventListener('DOMContentLoaded', () => {
    const totalCountSpan = document.getElementById('ua-total-count');
    const cards = document.querySelectorAll('.ua-card');
    const filterButtons = document.querySelectorAll('.ua-filtros-button button');
    const searchInput = document.getElementById('ua-search-input');

    let currentFilter = 'Todos';

    function applyFiltersAndSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        let visibleCount = 0;

        cards.forEach(card => {
            const cardTipo = card.dataset.tipo;

            const title = card.querySelector('.ua-card-title').textContent.toLowerCase();
            
            const contentElement = card.querySelector('.ua-card-main-content');
            const content = contentElement ? contentElement.textContent.toLowerCase() : '';

            const addressElement = card.querySelector('.ua-card-address');
            const address = addressElement ? addressElement.textContent.toLowerCase() : '';

            const filterMatch = (currentFilter === 'Todos' || cardTipo === currentFilter);
            const searchMatch = (
                title.includes(searchTerm) || 
                content.includes(searchTerm) || 
                address.includes(searchTerm)
            );

            const isVisible = filterMatch && searchMatch;

            card.style.display = isVisible ? 'flex' : 'none';

            if (isVisible) {
                visibleCount++;
            }
        });

        if (totalCountSpan) {
            totalCountSpan.textContent = visibleCount;
        }
    }

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            currentFilter = btn.dataset.filter;
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            applyFiltersAndSearch();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyFiltersAndSearch);
    }
    
    filterButtons.forEach(btn => {
        if(btn.dataset.filter === 'Todos') {
            btn.classList.add('active');
        }
    });
});