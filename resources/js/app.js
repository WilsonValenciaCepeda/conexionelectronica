import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Función de búsqueda con autocompletado
Alpine.data('searchProducts', () => ({
    query: '',
    results: [],
    loading: false,

    init() {
        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get('buscar');
        if (searchQuery) {
            this.query = '';
            this.results = [];
        }
    },

    async search() {
        if (this.query.length < 2) {
            this.results = [];
            return;
        }

        this.loading = true;
        try {
            const response = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`);
            const data = await response.json();
            this.results = data;
        } catch (error) {
            console.error('Error en la búsqueda:', error);
            this.results = [];
        } finally {
            this.loading = false;
        }
    },

    submitSearch() {
        if (this.query.trim()) {
            const searchQuery = this.query;
            this.query = '';
            this.results = [];
            const input = document.querySelector('input[name="buscar"]');
            if (input) {
                input.value = '';
            }
            window.location.href = `/tienda?buscar=${encodeURIComponent(searchQuery)}`;
        }
    },

    clearSearch() {
        this.query = '';
        this.results = [];
        const input = document.querySelector('input[name="buscar"]');
        if (input) {
            input.value = '';
        }
    },

    closeResults() {
        setTimeout(() => {
            this.results = [];
        }, 200);
    }
}));

Alpine.start();