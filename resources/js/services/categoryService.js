import api from './api';

const categoryService = {
    getAll() {
        return api.get('/categories');
    },

    getById(id) {
        return api.get(`/categories/${id}`);
    }
};

export default categoryService;