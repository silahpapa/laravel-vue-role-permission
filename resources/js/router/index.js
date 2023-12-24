import Departments from '../../app/dashboard/Departments/Departments.vue'
import Dashboard from '../../app/dashboard/Dashboard.vue'
import { createRouter, createWebHistory } from 'vue-router'
import Login from '../../app/auth/Login.vue'
import Roles from '../../app/dashboard/Roles/Roles.vue'
let routes = [
    {
        path: '/',
        component: Dashboard,
        name: 'Dashboard',
        meta: {
            layout: 'app',
            requiresAuth: true,
        },
        children: [
            {
                path: 'departments',
                component: Departments,
                name: 'Departments',
            },
            {
                path: 'roles',
                component: Roles,
                name: 'Roles',
            }
       ]
    },
    {
        path: '/login',
        component: Login,
        name: 'Login',
        meta: {
            layout: 'auth'
        }
    }
]
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
})
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!localStorage.getItem('access_token')) {
            next({
                path: '/login',
                params: { nextUrl: to.fullPath }
            })
        } else {
            next()
        }
    } else {
        next()
    }

})

export default router
