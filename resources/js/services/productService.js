import api from './api';

const productService = {
    getAll(page = 1) {
        return api.get(`/products?page=${page}`);
    },

    search(query) {
        return api.get(`/products/search/${query}`);
    },

    getById(id) {
        return api.get(`/products/${id}`);
    },

    create(data) {
        return api.post('/products', data);
    },

    update(id, data) {
        return api.put(`/products/${id}`, data);
    },

    delete(id) {
        return api.delete(`/products/${id}`);
    },

    getLowStock() {
        return api.get('/products/low-stock');
    }
};

export default productService;