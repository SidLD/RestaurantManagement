<template>
  <div class="row mt-5" style="color:blue;">
    <h3 class="pl-10 col-md-6">{{obj.name}}</h3>
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
            Rename {{title}}
          </v-card-title>

          <v-card-text>
              <v-text-field
                  v-if="type === 'category'"
                  label="Category Name"
                  :rules="rules"
                  hide-details="auto"
                  v-model="newName"
              ></v-text-field>
              <v-text-field
                  v-if="type === 'meal'"
                  label="Meal Name"
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
              @click="editMeal"
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
          Add Product for {{obj.name}}
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
                Are you sure to delete <br /> {{obj.name}}?
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
                  @click="deleteMeal"
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
    props : ['obj', 'type' , 'title'],
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
            token: window.localStorage.getItem('token'),
            newName: this.obj.name,
            rules: [
                value => !!value || 'Required.',
                value => (value && value.length >= 3) || 'Min 3 characters',
            ],
        }
    },
    methods:{
        getProducts(){
            axios.get(window.location.origin+ '/api/'+this.type+'/' + this.obj.id + '/product/selectable')
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
          axios.post(window.location.origin+'/api/'+this.type+'/' + this.obj.id + '/product/',productsIds)
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
        deleteMeal(){
            axios.delete(window.location.origin+'/api/'+this.type+'/' + this.obj.id)
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
        editMeal(){
            const newObj = {
                name : this.newName
            };
            axios.put(window.location.origin+ '/api/'+this.type+'/' + this.obj.id, newObj)
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
    },
    created(){
      console.log(this.obj)
      axios.defaults.headers.common['Authorization'] = "Bearer "+this.token; 
    }
}
</script>