import api from './api';

const saleService = {
    getAll(params = {}) {
        return api.get('/sales', { params });
    },

    getById(id) {
        return api.get(`/sales/${id}`);
    },

    create(data) {
        return api.post('/sales', data);
    },

    getDashboardStats() {
        return api.get('/dashboard/stats');
    }
};

export default saleService;