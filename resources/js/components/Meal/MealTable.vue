<template>
    <div>
        <div class="d-flex justify-content-end pt-2 pb-2">
            <AddMeal @update="render" />
        </div>
        <div>
            <div v-for="(meal, i) in meals" :key="i">
                <MealProductTable :meal="meal" @update="render"/>
            </div>
        </div>
    </div>
</template>
<script>
import MealProductTable from './MealProductTable.vue';
import AddMeal from './AddMeal.vue'

export default {
    components:{
        MealProductTable,
        AddMeal
    },
    data() {
        return {
            meals : {
            },
        }
    },
    methods: {
        initialize(){
            const token = window.localStorage.getItem('token');
            axios.get('api/meal', {headers: {"Authorization" : "Bearer " + token}})
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
        this.initialize();
    }
}
</script>