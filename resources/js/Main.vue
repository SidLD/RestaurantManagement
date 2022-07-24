<template>  
    <div>
        <div v-if="isLogin">  
            <Dashboard />
        </div>
        <div v-if="isLogin == false">
            <Login />
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
                isLogin : true
            }
        }
    }
</script>

<style>
    
</style>