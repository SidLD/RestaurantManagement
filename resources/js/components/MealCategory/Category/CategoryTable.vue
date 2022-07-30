<template>
    <div>
        <div class="d-flex justify-content-end pt-2 pb-2">
            <AddMeal @update="render" :type="'category'" :title="'Category'"/>
        </div>
        <div>
            <div v-for="(category, i) in categories" :key="i">
                <MealProductTable :obj="category" @update="render" :type="'category'" :title="'Category'"/>
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
            token : window.localStorage.getItem('token'),
            categories : {
            },
        }
    },
    methods: {
        initialize(){
            axios.get(window.location.origin+'/api/category')
            .then(res => {
                this.categories = res.data;
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