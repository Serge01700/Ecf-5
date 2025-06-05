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
                path: '/child',
                name: 'child',
                component: () => import ('../view/ChildrenSpace.vue')
            },
            {
                path: '/settings',
                name: 'settings',
                component: () => import ('../view/Settings.vue')
            },
            {
                path: '/parent',
                name: 'parent',
                component : () => import ('../view/ParentSpace.vue')
            },
            {
                path: '/schedule',
                name: 'schedule',
                component: () => import ('../view/Schedule.vue')
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router