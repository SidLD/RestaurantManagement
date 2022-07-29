<template>
  <div>
    <div>
        <MealAppBar :meal="meal" @update="update"/>
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
            v-for="(product,i) in meal.products"
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
import MealAppBar from './MealAppBar.vue'
export default {
    props: ['meal'],
    components: {
      MealAppBar
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
        axios.post('api/meal/' + this.meal.id + '/product/'+ id, {headers: {"Authorization" : "Bearer " + token}})
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