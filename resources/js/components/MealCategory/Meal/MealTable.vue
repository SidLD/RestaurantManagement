<template>
    <div>
        <div class="d-flex justify-content-end pt-2 pb-2">
            <AddMeal @update="render" :type="'meal'" :title="'Meal'"/>
        </div>
        <div>
            <div v-for="(meal, i) in meals" :key="i">
                <MealProductTable :obj="meal" @update="render" :type="'meal'" :title="'Meal'"/>
            </div>
        </div>
    </div>
</template>
<script>
import MealProductTable from '../MCProductTable.vue';
import AddMeal from '../MCAdd.vue'

export default {
    components:{
        MealProductTable,
        AddMeal
    },
    data() {
        return {
            token: window.localStorage.getItem('token'),
            meals : {
            },
        }
    },
    methods: {
        initialize(){
            axios.get('api/meal')
            .then(res => {
                this.meals = res.data;
            })
            .catch(error => {
                if(error.response.status === 401){
         alert("Unauthorized")
          window.location.href = '/login';
       }
                console.log(error)
            })
        },
        render(){
            this.initialize();
        }
      // Create an array the length of our items
      // with all values as true
     
    },
    created(){
        axios.defaults.headers.common['Authorization'] = "Bearer "+this.token; 
        this.initialize();
    }
}
</script>