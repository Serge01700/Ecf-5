import { createRouter, createWebHistory } from 'vue-router'
import DashboardLayout from '../layout/DashboardLayout.vue'
import HomeDashboard from '../view/HomeDashboard.vue'

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: DashboardLayout,
        children: [
            {
                path: '',  
                name: 'home',
                component: HomeDashboard
            },
            {
                path: '/parent',
                name: 'parent',
                component: () => import ('../view/ParentalSpace.vue')
            },
            {
                path: '/settings',
                name: 'settings',
                component: () => import ('../view/Settings.vue')
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router