import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.baseURL = '/api';

window.axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response) {
            console.error('API Error:', error.response.data.message || 'Something went wrong');
        }
        return Promise.reject(error);
    }
);