import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

api.interceptors.response.use(
    response => response.data,
    error => {
        const message = error.response?.data?.message || error.response?.data?.error || 'An error occurred';
        console.error('API Error:', message);
        return Promise.reject(error);
    }
);

export default api;