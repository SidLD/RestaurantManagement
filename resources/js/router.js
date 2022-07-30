import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);

import User from './components/User/User.vue'
import Admin from './components/Admin/Admin.vue'
import TableSetupTable from './components/Table/TableSetup.vue'
import EmployeeTable from './components/Employee/EmployeeTable.vue'
import CategoryTable from './components/MealCategory/Category/CategoryTable.vue'
import MealTable from './components/MealCategory/Meal/MealTable.vue'
import BookingTable from './components/Booking/BookingTable.vue'
import ProductTable from './components/Product/ProductTable.vue'
// import Main from './Main.vue'
const routes = [
        {
            path: '/user',
            component: User,
        },
        {
            path: '/employee',
            component: EmployeeTable
        },
        {
            path: '/admin',
            component: Admin
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
                path: "/booking",
            }
        },
]

let router = new Router({
    mode: 'history',
    routes,
})
router.beforeEach((to, from, next) => {
    console.log(to.path, from.path)
    if(to.path === "/"){
        if(window.localStorage.getItem('isLogin')){
            window.location.href = "/booking"
        }
        next()
    }else if(to.path === '/login'){
        if(window.localStorage.getItem('isLogin')){
            switch(from.path){
                case "/booking":
                    window.location.href = "/login";
                    break;
                case undefined:
                    window.location.href = "/booking";
                    break;
                case null:
                    window.location.href = "/booking";
                    break;
                default: 
                    window.location.href = "/booking"
                    break;
            }
        }
        next();
    }
    next();
});
export default router;