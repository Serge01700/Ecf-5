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
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router