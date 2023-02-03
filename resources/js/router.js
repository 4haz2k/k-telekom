import Vue from "vue";
import VueRouter from "vue-router"

Vue.use(VueRouter)

const router = new VueRouter ({
    mode: 'history',

    routes : [
        {
            path: '/user/login', component: () => import('./components/Login'),
            name: 'user.login'
        },
        {
            path: '/equipments', component: () => import('./components/Equipments'),
            name: 'equipments.list'
        },
        {
            path: '/equipments/add', component: () => import('./components/EquipmentAdd'),
            name: 'equipments.add'
        },
    ]
})

router.beforeEach((to, from, next) => {

    // Если роут не /user/login и отсутствует токен
    if (to.name !== 'user.login' && !localStorage.getItem('token')) {
        next({ name: 'user.login' })
    }

    if (to.name === 'user.login' && localStorage.getItem('token'))
        next({ name: 'equipments.list' })

    next()
})

export default router
