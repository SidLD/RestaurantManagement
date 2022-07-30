<template>
  <v-card
    class="mx-auto"
    max-width="300"
    style="margin-bottom: 2rem;"
  >
    <div class="img-wrapper">
      <img
      class="white--text align-end"
      height="150px"
      :src="getImage(product.img)"
    >
    </div>
    <v-card-title class="pb-0">
      {{product.name}}
    </v-card-title>

    <v-card-text class="text--primary">
      <p> ₱ {{product.price}}</p>
      <p v-if="product.display">Displayed</p>
      <p v-if="!product.display">Not Displayed</p>
    </v-card-text>

    <v-card-actions>
      <!-- Edit -->
      <template>
        <div class="text-center">
          <v-dialog
            v-model="editDialog"
            width="500"
          >
            <template v-slot:activator="{ onEdit, attrsEdit }">
              <v-btn
                color="yellow"
                text
                @click="editDialog = true"
                v-bind="attrsEdit"
                v-on="onEdit"
              >
                Edit
              </v-btn>
            </template>
            <v-card>
              <v-card-title class="text-h5 grey lighten-2">
                Edit {{product.name}}
              </v-card-title>
      
              <v-card-text>
                <v-container class="container-fluid">
                  <v-row>
                    <v-col cols="12" sm="12">
                      <v-text-field v-model="newProduct.name"
                        label="Product Name"
                      ></v-text-field>
                      <div :v-if="errorName">
                          <label class="error" v-for="error,index in errorName" :key="index"> 
                          {{error}}*
                          </label>
                      </div>
                    </v-col>
                    <v-col cols="12" sm="12">
                      <v-text-field v-model="newProduct.price"
                        label="Product Price"
                        type="number"
                      ></v-text-field>
                      <div :v-if="errorPrice">
                          <label class="error" v-for="error,index in errorPrice" :key="index"> 
                          {{error}}*
                          </label>
                      </div>
                    </v-col>
                    <v-col cols="12" sm="12">
                      <input type="file" class="form-control" @change="changeFile"/>
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
                  @click="editDialog = false"
                >
                  Cancel
                </v-btn>
                <v-btn
                  color="primary"
                  text
                  @click="editProduct"
                >
                  Save
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </template>
      <!-- Display -->
      <template>
        <div class="text-center">
          <v-dialog
            v-model="displayDialog"
            width="400"
          >
            <template v-slot:activator="{ onDisplay, attrsDisplay}">
              <v-btn
                color="orange"
                text
                @click="displayDialog = true"
                v-bind="onDisplay"
                v-on="attrsDisplay"
              >
                Display
              </v-btn>
            </template>
      
            <v-card>
              <v-card-title class="text-h5 grey lighten-2">
                The product <br /> {{product.name}} will be removed from 
                Meal and Product but will not get deleted.
              </v-card-title>
      
              <v-divider></v-divider>
      
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="blue"
                  text
                  @click="displayDialog = false"
                >
                  Cancel
                </v-btn>
                <v-btn
                  color="red"
                  text
                  @click="displayProduct"
                >
                  Confirm
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </template>
      <!-- Delete -->
      <template>
        <div class="text-center">
          <v-dialog
            v-model="deleteDialog"
            width="350"
          >
            <template v-slot:activator="{ onDelete, attrsDelete}">
              <v-btn
                color="red"
                text
                @click="deleteDialog = true"
                v-bind="onDelete"
                v-on="attrsDelete"
              >
                Delete
              </v-btn>
            </template>
      
            <v-card>
              <v-card-title class="text-h5 grey lighten-2">
                Are you sure to delete <br /> {{product.name}}?
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
                  @click="deleteProduct"
                >
                  Confirm
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </template>
    </v-card-actions>
  </v-card>
</template>
<script>
    export default {
        props : ['product'],
        data(){
            return {
              editDialog:false,
              deleteDialog:false,
              displayDialog: false,
              file: null,
              errorName : null,
              errorPrice : null,
              newProduct: {
                name: this.product.name,
                price: this.product.price
              },
            }
        },
        methods:{
          resetError(){
            this.errorName = null;
            this.errorPrice = null;
            this.errorFile = null;
          },
          displayError(error){
            if(error.response.data.errors.name){
              this.errorName = error.response.data.errors.name;
            }
            if(error.response.data.errors.price){
              this.errorPrice = error.response.data.errors.price;
            }
          },
          changeFile(e){
              this.file = e.target.files[0];
          },
          deleteProduct(){
            const token = window.localStorage.getItem('token');
            this.deleteDialog = false;
            axios.delete('api/product/' + this.product.id, {headers: {"Authorization" : "Bearer " + token}})
            .then(res => {
              if(res.data.res === 'ok'){
                alert(res.data.msg);
                this.$emit('update', this.product);
              }else{
                alert(res.data.msg);
              }
            })
            .catch(error => {
              if(error.response.status === 401){
                alert("Unauthorized")
                 window.location.href = '/login';
              }
            })
          },
          displayProduct(){
            const token = window.localStorage.getItem('token');
            axios.post('api/product/display/' + this.product.id, {headers: {"Authorization" : "Bearer " + token}})
            .then(res => {
              console.log(res);
              alert(res.data.msg)
              this.displayDialog = false;
              this.$emit('update', this.product);
            })
            .catch(error => {
              if(error.response.status === 401){
                alert("Unauthorized")
                window.location.href = '/login';
              }
              console.log(error)
            })
          },
          editProduct(){
            let editedProduct = new FormData()
            editedProduct.append('price', this.newProduct.price) 
            editedProduct.append('name', this.newProduct.name)
            this.file !== null ? editedProduct.append('file', this.file) : null
            const token = window.localStorage.getItem('token');
            const config = {
              headers:{
                'content-type' : 'multipart/form-data',
                'Authorization' : 'Bearer ' + token
              }
            }
            // console.log(this.newProduct.price);
            axios.post('api/product/update/' + this.product.id, editedProduct, config)
            .then(res => {
              alert(res.data.msg)
              this.editDialog = false;
              this.$emit('update', this.product);
            })
            .catch(error => {
              this.displayError(error)
            })
            
          },
          getImage(filename){
            return "./storage/images/" + filename;
          }
        },
    }
</script>
<style scoped>
  .img-wrapper{
      width: 100%;
      display: flex;
      flex-direction: row;
      justify-content: center;
      align-items: center;
  }
  .v-application .error{
    color: red !important; 
    font-size:0.8rem;
    background-color: white !important;
  }
</style>