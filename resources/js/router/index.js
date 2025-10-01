import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        name: 'Dashboard',
        component: () => import('../views/Dashboard.vue')
    },
    {
        path: '/pos',
        name: 'POS',
        component: () => import('../views/POS.vue')
    },
    {
        path: '/products',
        name: 'Products',
        component: () => import('../views/Products.vue')
    },
    {
        path: '/sales',
        name: 'Sales',
        component: () => import('../views/Sales.vue')
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;