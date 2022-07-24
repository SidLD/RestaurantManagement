import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);

import UserTable from './components/User/UserTable.vue'
import AdminTable from './components/Admin/AdminTable.vue'
import EmployeeTable from './components/Employee/EmployeeTable.vue'
import CategoryTable from './components/Category/CategoryTable.vue'
import MealTable from './components/Meal/MealTable.vue'
import BookingTable from './components/Booking/BookingTable.vue'
import ProductTable from './components/Product/ProductTable.vue'
import Login from './components/pages/Login';
import Dashboard from './components/pages/Dashboard';

const routes = [
        {
            path: '/dashboard',
            component: Dashboard
        },
        {
            path: '/login',
            component: Login
        },
        {
            path: '/users',
            component: UserTable
        },
        {
            path: '/employees',
            component: EmployeeTable
        },
        {
            path: '/admins',
            component: AdminTable
        },
        {
            path: '/products',
            component: ProductTable
        },
        {
            path: '/meals',
            component: MealTable
        },
        {
            path: '/categories',
            component: CategoryTable
        },
        {
            path: '/bookings',
            component: BookingTable
        },
]

export default new Router({
    mode: 'history',
    routes,
    // linkActiveClass : 'active_link'
})