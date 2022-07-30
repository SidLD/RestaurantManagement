<template>
  <div>
    <div>
        <MealActions :obj="obj" @update="update" :type="type" :title="title"/>
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
            v-for="(product,i) in obj.products"
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
import MealActions from './MCAction.vue'
export default {
    props: ['obj', 'type', 'title'],
    components: {
      MealActions
    },
    data(){
       return {
        token : window.localStorage.getItem('token')
       } 
    },
    methods:{
      update(){
        this.$emit('update');
      },
      removeProduct(id){
        axios.post(window.location.origin+ '/api/'+this.type+'/' + this.obj.id + '/product/'+ id)
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
  },
  created(){
    axios.defaults.headers.common['Authorization'] = "Bearer "+this.token; 
  }
}
</script>