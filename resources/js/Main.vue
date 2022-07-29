<template>  
    <div>
        <div v-if="isLogin">
            <Dashboard />
        </div>
        <div v-if="!isLogin">
            <Login/>
        </div>
    </div>
</template>

<script>
    import Dashboard from './components/pages/Dashboard.vue'
    import Login from './components/pages/Login.vue'
    export default {
        components: {
            Dashboard,
            Login
        },
        data: function() {
            return {
                drawer : null,
                token: localStorage.getItem('token'),
                isLogin: localStorage.getItem('isLogin')
            }
        },
        methods:{
            loggedIn(){
                axios.post
            }
        },
        created(){
            axios.defaults.headers.common['Authorization'] = "Bearer "+this.token; 
            console.log(this.isLogin);
        }
    }
</script>

<style>
    
</style>