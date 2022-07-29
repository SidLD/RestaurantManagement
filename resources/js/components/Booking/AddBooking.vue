<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="800"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn 
            rounded
            v-bind="attrs"
            v-on="on"
            color="primary"
            v-if="type === 'edit'"
        >
          <v-icon left>
            mdi-pencil
          </v-icon>
            Edit
        </v-btn>
        <v-btn 
            rounded
            v-bind="attrs"
            v-on="on"
            color="primary"
            v-if="type === 'add'"
        >
          <v-icon left>
            mdi-pencil
          </v-icon>
            Add
        </v-btn>
      </template>

      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          New Book
        </v-card-title>

        <v-card-text>
            <v-container>
                <v-row>
                    <v-col cols="4">
                        <v-combobox
                            v-if="type === 'edit'"
                            disabled
                            v-model="selectedUser"
                            clearable
                            :items="users"
                            label="User"
                        ></v-combobox>
                        <v-combobox
                            v-if="type === 'add'"
                            v-model="selectedUser"
                            clearable
                            :items="users"
                            label="User"
                        ></v-combobox>
                    </v-col>
                     <v-col cols="3">
                        <v-menu
                      v-model="menu2"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      transition="scale-transition"
                      offset-y
                      min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="date"
                            label="Date"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          v-model="date"
                          @input="menu2 = false"
                        ></v-date-picker>
                      </v-menu>
                    </v-col>
                    <v-col cols="5">
                        <v-combobox
                            v-model="selectedTable"
                            clearable
                            :items="tables"
                            label="Table"
                        ></v-combobox>
                    </v-col>
  
                    <v-col cols="6">
                        <v-combobox
                            v-model="selectedProduct"
                            clearable
                            :items="products"
                            label="Product"
                        ></v-combobox>
                    </v-col>
                    <!-- Quantity -->
                    <v-col cols="3">
                      <v-text-field 
                        label="Quantity"
                        type="number"
                        v-model="qty"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="3">
                        <v-btn 
                          color="secondary"
                          @click="addProductToCart"
                        >Add Product</v-btn>
                    </v-col>
                    <v-col cols="6">
                      <table class="table table-dark">
                        <thead>
                          <tr>
                            <td> Id </td>
                            <td> Product </td>
                            <td> Quantity </td>
                            <td> Total </td>
                            <td> Action </td>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(product, i) in productAdded" :key="i">
                            <td> {{product.id}} </td>
                            <td> {{product.name}} </td>
                            <td> {{product.qty}} </td>
                            <td> {{product.total}} </td>
                            <td> <v-btn @click="removeProduct(i)"> Remove </v-btn></td>
                          </tr>
                        </tbody>
                      </table>
                    </v-col>
                    <v-col cols="3">
                      <v-text-field 
                            label="Total"
                            type="number"
                            disabled
                            v-model="total"
                        ></v-text-field>
                    </v-col>
                   <v-col cols="3">
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
            @click="reset"
          >
            Reset
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
    props: ['type', 'id'],
    data () {
      return {
        dialog: false,
        menu2: false,
        date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        selectedUser : "",
        selectedTable : "",
        selectedProduct : "",
        productAdded: [],
        datetime: null,
        qty: 1,
        total : 0,
        tables: this.passTables,
        products: this.passProducts,
        users : this.passUsers,
      }
    },
    methods:{
        async getBook(){
          await axios.get('api/booking/'+this.id)
                .then(res => {
                this.selectedUser = res.data.user_id + ". " + res.data.user.first_name + " " + res.data.user.last_name;
                this.date = res.data.date;
                this.selectedTable = res.data.tables[0].id + ". " + res.data.tables[0].name + " for " + res.data.tables[0].number_of_seats + " Seats / " +res.data.tables[0].type;
                this.total = res.data.total;
                this.initiateAddedProduct(res.data.products)
          })
        },

        async getUser(){
          await axios.get('api/user/user')
          .then(res => {
            this.users = []
            res.data.forEach(user => {
              const userWithId = user.id + ". " + user.first_name + " " + user.last_name;
              this.users.push(userWithId)
            })
          })
        },
        async getProducts(){
            await axios.get('api/product')
            .then(res => {
                this.products = []
                res.data.forEach(product => {
                  const productWithIds = product.id + ". " + product.name + " - ₱" +product.price;
                  this.products.push(productWithIds)
                })
            })
        },
        async getTables(){
            await axios.get('api/table')
            .then(res => {  
                this.tables = []
                res.data.forEach(table => {
                  if(table.status === 1){
                    const tableWithIds = table.id + ". " + table.name + " for " + table.number_of_seats + " Seats / " +table.type;
                    this.tables.push(tableWithIds)
                  }
                })
            })
        },
        initiateAddedProduct(products){
          products.forEach(product => {
            const productWithIds = product.id + ". " + product.name + " - ₱" +product.price
            this.selectedProduct = productWithIds;;
            this.qty = product.pivot.qty;
            this.addProductToCart();
          });
        },
        addProductToCart(){
          const newProduct = {
            id: this.selectedProduct[0],
            name : this.selectedProduct,
            qty : this.qty,
            total: Number(this.selectedProduct.substring(this.selectedProduct.indexOf("₱")+1, this.selectedProduct.length -1)) * this.qty 
          }
          this.productAdded.push(newProduct)
          this.total += newProduct.total;
          this.selectedProduct = null
          this.qty = null
        },
        removeProduct(index){
          const product = this.productAdded[index];
          this.total -= product.total;
          this.productAdded.splice(index, 1)
        },
        async reset(){
          this.selectedUser = "",
          this.selectedTable = "",
          this.selectedProduct = "",
          this.productAdded = [],
          this.qty = 1,
          this.tables = null,
          this.products = null,
          this.users =  null,
          this.selectedTable = null
        },
        save(){
          try {
            const book = {
              userId : Number(this.selectedUser[0]),
              date : this.date,
              tableId : Number(this.selectedTable[0]),
              products : this.productAdded,
              total: this.total
            }
              if(this.type === "add"){
                axios.post('api/booking', book)
                .then(res => {
                  alert(res.data.msg);
                  this.$emit('update')
                })
                .catch(error => {
                  alert("Invalid inputs")
                  console.log(error.response.data);
                })
            }else {
              axios.put('api/booking/'+this.id, book)
              .then(res => {
                alert(res.data.msg);
                this.$emit('update')
              })
              .catch(error => {
                alert("Invalid inputs")
                console.log(error.response.data);
              })
            }
          }catch(err) {
            alert("Invalid Inputs")
          }
        },
    },
    async created(){
        if(this.type == "edit"){
          await this.getBook();
        }else{
          await this.getUser();
          await this.getProducts()
          await this.getTables()
        }
    }
  }
</script>