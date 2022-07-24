      <template>
        <div class="text-center">
          <v-dialog
            v-model="dialog"
            width="500"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="orange"
                text
                @click="dialog = true"
                v-bind="attrs"
                v-on="on"
              >
                New Product
              </v-btn>
            </template>
            <v-card>
              <v-card-title class="text-h5 grey lighten-2">
                New Product
              </v-card-title>
      
              <v-card-text>
                <v-container class="container-fluid">
                  <v-row>
                    <v-col cols="12" sm="12">
                      <v-text-field v-model="product.name"
                        label="Product Name"
                      ></v-text-field>
                      <div :v-if="errorName">
                          <label class="error" v-for="error,index in errorName" :key="index"> 
                          {{error}}*
                          </label>
                      </div>
                    </v-col>
                    <v-col cols="12" sm="12">
                      <v-text-field v-model="product.price"
                        label="Product Price"
                        type="number"
                        required
                      ></v-text-field>
                      <div :v-if="errorPrice">
                          <label class="error" v-for="error,index in errorPrice" :key="index"> 
                          {{error}}*
                          </label>
                      </div>
                    </v-col>
                    <v-col cols="12" sm="12">
                      <input type="file" class="form-control" @change="changeFile"/>
                      <div :v-if="errorFile">
                          <label class="error" v-for="error,index in errorFile" :key="index"> 
                          {{error}}*
                          </label>
                      </div>
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
                  @click="dialog = false"
                >
                  Cancel
                </v-btn>
                <v-btn
                  color="primary"
                  text
                  @click="save"
                >
                  Save
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </template>

<script>
export default {
    data(){
      return{
        product : {
            name : "",
            price :0,
            file : null
        },
        dialog : false,
        errorName : null,
        errorPrice : null,
        errorFile : null,
        
      }  
    },
    methods: {
        resetError(){
            this.errorName = null;
            this.errorPrice = null;
            this.errorFile = null;
        },
        displayError(error){
            if(error.response.data.errors.name){
              this.errorName = error.response.data.errors.name;
            }else{
              this.errorName = null
            }
            if(error.response.data.errors.price){
              this.errorPrice = error.response.data.errors.price;
            }else{
              this.errorPrice = null;
            }
            if(error.response.data.errors.file){
              this.errorFile = error.response.data.errors.file;
            }else{
              this.errorFile = null;
            }

        },
        changeFile(e){
              this.product.file = e.target.files[0];
        },
        reset(){
            this.product.name = "",
            this.product.price = 0,
            this.product.file = null
        },
        save(){
            let newProduct = new FormData()
            newProduct.append('name', this.product.name)
            newProduct.append('price', this.product.price) 
            newProduct.append('file', this.product.file)
            const config = {
              headers:{
                'content-type' : 'multipart/form-data'
              }
            }
            axios.post('api/product/', newProduct, config)
            .then(res => {
              console.log(res)
              alert(res.data.msg)
              this.dialog = false;
              this.$emit('update');
            })
            .catch(error => {
              this.displayError(error)
            })
        },
        
    }
}
</script>
<style scoped>
  .v-application .error{
    color: red !important; 
    font-size:0.8rem;
    background-color: white !important;
  }
</style>