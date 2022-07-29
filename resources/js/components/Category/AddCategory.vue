<template>
<div class="col-sm-12 col-md-4">
          <v-dialog
              v-model="dialog"
              width="500"
          >
        <template v-slot:activator="{ on, attrs}">
          <v-btn
            color="red lighten-2"
            v-bind="attrs"
            v-on="on"
            @click="dialog = true"
          >
          <!-- <v-icon>{{icons.mdiPencil}}</v-icon> -->
            New Category
          </v-btn>
        </template>

        <v-card>
          <v-card-title class="text-h5 grey lighten-2">
            New Category
          </v-card-title>

          <v-card-text>
              <v-text-field
                  label="Category Name"
                  :rules="rules"
                  hide-details="auto"
                  v-model="newName"
              ></v-text-field>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              text
              @click="newCategory"
            >
              Save
            </v-btn>
          </v-card-actions>
        </v-card>
          </v-dialog>
        </div>
</template>
<script>
import axios from 'axios';

  export default{
    data(){
      return {
        dialog : false,
        newName: "",
        rules: [
                value => !!value || 'Required.',
                value => (value && value.length >= 3) || 'Min 3 characters',
            ],
      }
    },
    methods:{
      newCategory(){
        const category = {
          name: this.newName
        }
        const token = window.localStorage.getItem('token');
        axios.post('api/category', category, {headers: {"Authorization" : "Bearer " + token}})
        .then(res => {
          alert(res.data.msg)
          this.dialog = false
          this.update();
        })
        .catch(error => {
          if(error.response.status === 401){
         alert("Unauthorized")
          window.location.href = '/login';
       }
          alert("Something Went Wrong")
        })
      },
      update(){
        this.$emit('update');
      }
    }
  }
</script>