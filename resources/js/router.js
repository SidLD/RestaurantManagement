import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);

import UserTable from './components/User/UserTable.vue'
import AdminTable from './components/Admin/AdminTable.vue'
import TableSetupTable from './components/Table/TableSetup.vue'
import EmployeeTable from './components/Employee/EmployeeTable.vue'
import CategoryTable from './components/Category/CategoryTable.vue'
import MealTable from './components/Meal/MealTable.vue'
import BookingTable from './components/Booking/BookingTable.vue'
import ProductTable from './components/Product/ProductTable.vue'
// import Main from './Main.vue'
const routes = [
        {
            path: '/user',
            component: UserTable,
        },
        {
            path: '/employee',
            component: EmployeeTable
        },
        {
            path: '/admin',
            component: AdminTable
        },
        {
            path: '/product',
            component: ProductTable
        },
        {
            path: '/meal',
            component: MealTable
        },
        {
            path: '/category',
            component: CategoryTable
        },
        {
            path: '/table',
            component: TableSetupTable
        },
        {
            path: '/booking',
            component: BookingTable
        },
        {
            path: '/:random',
            redirect: {
                path: '/booking'
            }
        }
]

let router = new Router({
    mode: 'history',
    routes,
    // linkActiveClass : 'active_link'
})


// router.beforeEach((to, from, next)=>{
//     if(to.path === '/login'){
//         if(window.localStorage.getItem('isLogin') !== true){
//             window.location.href = "dashboard";
//         }
//     }else{
//         next();
//     }
// })
export default router;