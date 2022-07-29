<template>
    <div>
        <div class="d-flex justify-content-end pt-2 pb-2">
            <AddCategory @update="render" />
        </div>
        <div>
            <div v-for="(category, i) in categories" :key="i">
                <CategoryProductTable :category="category" @update="render"/>
            </div>
        </div>
    </div>
</template>
<script>
import CategoryProductTable from './CategoryProductTable.vue';
import AddCategory from './AddCategory.vue'

export default {
    components:{
        CategoryProductTable,
        AddCategory
    },
    data() {
        return {
            categories : {
            },
        }
    },
    methods: {
        initialize(){
            const token = window.localStorage.getItem('token');
            axios.get('api/category', {headers: {"Authorization" : "Bearer " + token}})
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
        this.initialize();
    }
}
</script>