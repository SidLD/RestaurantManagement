<template>
    <div>
        <v-toolbar flat>
            <v-toolbar-title>
                Product Table
            </v-toolbar-title>
            <v-divider
                class="mx-4"
                inset
                vertical
            >
            </v-divider>
            <v-spacer></v-spacer>

            <AddProduct @update="render"/>

        </v-toolbar>
        <v-spacer style="padding-bottom: 2rem;"></v-spacer>
        <v-container>
        <v-layout row wrap>
            <ProductCard  @update="render" v-for="product in products" :key="product.id" :product="product"/>           
        </v-layout>
    </v-container>
    </div>
</template>
<script>
import ProductCard from './ProductCard.vue'
import AddProduct from './AddProduct.vue'
export default {
    components:{
        ProductCard,
        AddProduct
    },
    data() {
        return {
            products : [],
            alignments: [
                'start',
                'center',
                'end',
      ],
        }
    },
    methods: {
        initialize(){
            const token = window.localStorage.getItem('token');
            axios.get('api/product', {headers: {"Authorization" : "Bearer " + token}})
            .then(res => {
                this.products = res.data;
            })
            .catch(error => {
                if(error.response.status === 401){
                  alert("Unauthorized")
                   window.location.href = '/login';
                }
            })
        },
        render(){
            this.initialize();
        }
    },
    created () {
      this.initialize()
    },
}
</script>
<style>

</style>