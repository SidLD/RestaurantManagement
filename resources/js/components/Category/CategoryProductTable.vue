<template>
  <div>
    <div>
        <CategoryAppBarVue :category="category" @update="update"/>
    </div>
    <v-simple-table>
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-left">
              Product Name
            </th>
            <th class="text-left">
              Action
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(product,i) in category.products"
            :key="i"
          >
            <td>{{ product.name }}</td>
            <td><v-btn @click="removeProduct(product.id)">Remove</v-btn></td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
  </div>
</template>
<script>
import CategoryAppBarVue from './CategoryAppBar.vue'
export default {
    props: ['category'],
    components: {
      CategoryAppBarVue
    },
    data(){
       return {

       } 
    },
    methods:{
      update(){
        this.$emit('update');
      },
      removeProduct(id){
        const token = window.localStorage.getItem('token');
        axios.post('api/category/' + this.category.id + '/product/'+ id, {headers: {"Authorization" : "Bearer " + token}})
        .then(res => {
          alert(res.data.msg)
          this.update();
        })
        .catch(error => {
          if(error.response.status === 401){
            alert("Unauthorized")
             window.location.href = '/login';
          }
          alert("Something Went Wrong")
        })
      } 
    }
}
</script>