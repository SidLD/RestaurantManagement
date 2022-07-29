<template>
  <v-data-table
    :headers="headers"
    :items="bookings"
    sort-by="calories"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>Bookings</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <AddBooking 
              :id="0" 
              :type="add" 
              @update="render" />
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <AddBooking 
        @update="render"
        :id="item.id" 
        :type="edit"/> 
      <DeleteBooking :type="booking" 
        :id="item.id" 
        :key="passUsers" />
    </template>
    <template v-slot:no-data>
      <v-btn
        color="primary"
        @click="initialize"
      >
        Reset
      </v-btn>
    </template>
  </v-data-table>
</template>
<script>
import AddBooking from './AddBooking.vue'
import DeleteBooking from '../DeleteDialog.vue'
  export default {
    components: { AddBooking , DeleteBooking},
    data() {
      return{
        renderChild : 0,
        passProducts: null,
        passUsers: null,
        passTables: null,
        products : [],
        users: [],
        tables: [],
        childKey : null,
        add: "add",
        edit : "edit",
        dialog: false,
        dialogDelete: false,
        booking : "booking", 
        token: localStorage.getItem('token'),
        headers: [
            {
                text: "ID",
                align: "start",
                sortable: false,
                value: "id",
            },
            { text: "User", value: "username" },
            { text: "Status", value: "status" },
            { text: "Date", value: "date" },
            { text: "Table", value: "tables[0].name" },
            { text: "Time", value: "tables[0].type" },
            { text: "Products", value: "newProducts" },
            { text: "Total", value: "total" },
            { text: "Actions", value: "actions", sortable: false },
        ],
        bookings: [],
    }},

    methods: {
        async initialize() {
          await axios.get("api/booking")
              .then(res => {
                this.bookings = res.data;
                this.bookings.forEach(book => {
                    book.total = "₱" + book.total;
                    book.newProducts = [];
                    book.products.forEach(product => {
                        const product_temp = " " + product.name + " x" + product.pivot.qty + " ";
                        book.newProducts.push(product_temp);
                    });
                });
              })
              .catch(function(error){
                  if(error.response.status === 401){
                    alert("Unauthorized")
                    window.location.href = '/login';
                  }
              })
        },
        async getUser(){
          await axios.get('api/user/user')
          .then(res => {
            // this.passUsers = res.data
            this.users = []
            res.data.forEach(user => {
              const userWithId = user.id + ". " + user.first_name + " " + user.last_name
              this.users.push(userWithId)
            })
          })
          .catch(error => {
            if(error.response.status === 401){
                alert("Unauthorized")
              window.location.href = '/login';
            }
          })
        },
        async getProducts(){
            await axios.get('api/product')
            .then(res => {
                // this.passProducts = res.data;
                this.products = []
                res.data.forEach(product => {
                  const productWithIds = product.id + ". " + product.name + " - ₱" +product.price;
                  this.products.push(productWithIds)
                })
            })
            .catch(error => {
              if(error.response.status === 401){
                    alert("Unauthorized")
                  window.location.href = '/login';
              }
            })
        },
        async getTables(){
            await axios.get('api/table',)
            .then(res => {  
                // this.passTables = res.data;
                this.tables = []
                res.data.forEach(table => {
                  if(table.status === 1){
                    const tableWithIds = table.id + ". " + table.name + " for " + table.number_of_seats + " Seats / " +table.type;
                    this.tables.push(tableWithIds)
                  }
                })
            })
            .catch(error => {
              if(error.response.status === 401){
                    alert("Unauthorized")
                  window.location.href = '/login';
              }
            })
        },
        render(){
          this.initialize();
        }
    },
    async created() {
        axios.defaults.headers.common['Authorization'] = "Bearer "+this.token; 
        await this.initialize();
        await this.getUser(); 
        await this.getProducts();
        await this.getTables();
    },
}
</script>
<style>
.table tbody tr td {
  white-space: pre-wrap !important
}
</style>
