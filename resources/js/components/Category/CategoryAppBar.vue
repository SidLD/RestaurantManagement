<template>
  <div class="row mt-5" style="color:blue;">
    <h3 class="pl-10 col-md-6">{{category.name}}</h3>
    <div class="row d-flex col-md-6 flex-row mb-6">
        <!-- Edit Category  -->
        <div class="col-sm-12 col-md-4">
          <v-dialog
              v-model="editDialog"
              width="500"
          >
        <template v-slot:activator="{ onEdit, attrsEdit }">
          <v-btn
            color="red lighten-2"
            v-bind="attrsEdit"
            v-on="onEdit"
            @click="editDialog = true"
          >
          <v-icon>{{icons.mdiPencil}}</v-icon>
            Edit
          </v-btn>
        </template>

        <v-card>
          <v-card-title class="text-h5 grey lighten-2">
            Rename Category
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
              @click="editCategory"
            >
              Save
            </v-btn>
          </v-card-actions>
        </v-card>
          </v-dialog>
        </div>
        <!-- Add Product -->
        <div class="col-sm-12 col-md-4">
         <v-dialog
            v-model="addProductDialog"
            width="500"
        >
      <template v-slot:activator="{ onEdit, attrsEdit }">
        <v-btn
          color="red lighten-2"
          dark
          v-bind="attrsEdit"
          v-on="onEdit"
          @click="getProducts"
        >
        <v-icon>{{icons.mdiCartPlus}}</v-icon>
            Add Product
        </v-btn>
      </template>

      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Add Product in Category {{category.name}}
        </v-card-title>

        <v-card-text>
            <v-container fluid>
              <v-row>
                <v-col cols="12">
                  <v-combobox
                    v-model="select"
                    clearable
                    :items="items"
                    label="Combobox"
                    multiple
                  ></v-combobox>
                </v-col>
              </v-row>
            </v-container>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            text
            @click="addProduct"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
        </v-dialog>
        </div>
        <!-- Delete -->
        <div class="col-sm-12 col-md-4">
        <div class="text-center">
          <v-dialog
            v-model="deleteDialog"
            width="350"
          >
            <template v-slot:activator="{ onDelete, attrsDelete}">
              <v-btn
                color="red"
                
                @click="deleteDialog = true"
                v-bind="onDelete"
                v-on="attrsDelete"
              >
              <v-icon>{{icons.mdiDelete}}</v-icon>
                Delete
                <v-icon>

                </v-icon>
              </v-btn>
            </template>
      
            <v-card>
              <v-card-title class="text-h5 grey lighten-2">
                Are you sure to delete <br /> {{category.name}}?
              </v-card-title>
      
              <v-divider></v-divider>
      
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="blue"
                  text
                  @click="deleteDialog = false"
                >
                  Cancel
                </v-btn>
                <v-btn
                  color="red"
                  text
                  @click="deleteCategory"
                >
                  Confirm
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {
    mdiAccount,
    mdiPencil,
    mdiCartPlus,
    mdiDelete,
  } from '@mdi/js'
export default {
    props : ['category'],
    data() {
        return {
            icons : {
              mdiAccount,
              mdiPencil,
              mdiDelete,
              mdiCartPlus
            },
            editDialog : false,
            deleteDialog: false,
            addProductDialog : false,
            select: [],
            items: [],
            newName: this.category.name,
            rules: [
                value => !!value || 'Required.',
                value => (value && value.length >= 3) || 'Min 3 characters',
            ],
        }
    },
    methods:{
        getProducts(){
            const token = window.localStorage.getItem('token');
            axios.get('api/category/' + this.category.id + '/product/selectable', {headers: {"Authorization" : "Bearer " + token}})
            .then(res => {
                this.addProductDialog = true;
                res.data.forEach(product => {
                  const productNameAndId = product.id + " " + product.name;
                  this.items.push(productNameAndId);
                });
            })
            .catch(error => {
              if(error.response.status === 401){
                  alert("Unauthorized")
                  window.location.href = '/login';
              }
              console.log(error.response)
            })
        },
        addProduct(){
          //This may look redundant but this will make it easier to extract array of ids in the backend
          let ids = [];
          this.select.forEach(element => {
            ids.push(element[0])
          });
          const productsIds = {
            ids : ids
          }
          const token = window.localStorage.getItem('token');
          axios.post('api/category/' + this.category.id + '/product/',productsIds, {headers: {"Authorization" : "Bearer " + token}})
            .then(res => {
                alert(res.data.msg)
                this.addProductDialog = false;
                this.select = [];
                this.items = [];
                this.getProducts();
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
        deleteCategory(){
          const token = window.localStorage.getItem('token');
            axios.delete('api/category/' + this.category.id, {headers: {"Authorization" : "Bearer " + token}})
            .then(res => { 
                alert(res.data.msg)
                this.deleteDialog = false;
                this.update();
            })
            .catch(error => {
              if(error.response.status === 401){
         alert("Unauthorized")
          window.location.href = '/login';
       }
                console.log(error.response)
            })
        },
        editCategory(){
            const category = {
                name : this.newName
            };
            const token = window.localStorage.getItem('token');
            axios.put('api/category/' + this.category.id, category, {headers: {"Authorization" : "Bearer " + token}})
            .then(res => { 
                alert(res.data.msg)
                this.editDialog = false;
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