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
            axios.get('api/product')
            .then(res => {
                this.products = res.data;
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