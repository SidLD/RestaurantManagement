<template>
  <v-data-table
    :headers="headers"
    :items="tables"
    sort-by="calories"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>My CRUD</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="primary"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
            >
              New Item
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="text-h5">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.name"
                      label="Name"
                    ></v-text-field>
                    <div :v-if="errorName">
                      <span class="error" v-for="error,index in errorName" :key="index"> 
                        {{error}}*
                      </span>
                    </div>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.type"
                      label="Meal Type"
                      hint="lunch, dinner, breakfast"
                    ></v-text-field>
                    <div :v-if="errorType">
                      <span class="error" v-for="error,index in errorType" :key="index"> 
                        {{error}}*
                      </span>
                    </div>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                  <v-select
                    :items="status"
                    v-model="editedItem.status"
                    label="Status"
                  ></v-select>
                  <div :v-if="errorStatus">
                      <span class="error" v-for="error,index in errorStatus" :key="index"> 
                        {{error}}*
                      </span>
                    </div>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.number_of_seats"
                      type="number"
                      label="Seats"
                    ></v-text-field>
                    <div :v-if="errorNumberOfSeats">
                      <span class="error" v-for="error,index in errorNumberOfSeats" :key="index"> 
                        {{error}}*
                      </span>
                    </div>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="blue darken-1"
                text
                @click="close"
              >
                Cancel
              </v-btn>
              <v-btn
                color="blue darken-1"
                text
                @click="save"
              >
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        small
        @click="deleteItem(item)"
      >
        mdi-delete
      </v-icon>
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
  export default {
    data: () => ({
      dialog: false,
      dialogDelete: false,
      status : ['Not Available', 'Available'],
      headers: [
        {
          text: 'Name',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Meal Time', value: 'type' },
        { text: 'Status', value: 'status' },
        { text: 'Seats', value: 'number_of_seats' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      tables: [],
      errorName : null,
      errorType : null,
      errorStatus : null,
      errorNumberOfSeats : null,
      editedIndex: -1,
      editedItem: {
        name: '',
        type: '',
        status: '',
        number_of_seats: 0,
      },
      defaultItem: {
        name: '',
        type: '',
        status: '',
        number_of_seats: 0,
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
    },

    created () {
      this.initialize()
    },

    methods: {
      initialize () {
        const token = window.localStorage.getItem('token');
        axios.get('api/table', {headers: {"Authorization" : "Bearer " + token}})
        .then(res => {
          this.tables = res.data;
          this.tables.forEach(element => {
            element.status = this.status[element.status];
          });
        })
        .catch(error => {
          if(error.response.status === 401){
                alert("Unauthorized")
              window.location.href = '/login';
          }
          console.log(error)
        })
      },
      
      resetError(){
        this.errorName = null;
        this.errorType = null;
        this.errorStatus = null;
        this.errorNumberOfSeats = null;
      },
      displayError(error){
        if(error.response.data.errors.name){
          this.errorName = error.response.data.errors.name;
        }
        if(error.response.data.errors.type){
          this.errorType = error.response.data.errors.type;
        }
        if(error.response.data.errors.status){
          this.errorStatus = error.response.data.errors.status;
        }
        if(error.response.data.errors.number_of_seats){
          this.errorNumberOfSeats = error.response.data.errors.number_of_seats;
        }
      },
      editItem (item) {
        this.editedIndex = this.tables.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        this.editedIndex = this.tables.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
      },

      deleteItemConfirm () {
        const token = window.localStorage.getItem('token');
        axios.delete('api/table/'+ this.editedItem.id, {headers: {"Authorization" : "Bearer " + token}})
        .then(res => {
          alert(res.data.msg);
          this.initialize()
        })
        .catch(error => {
          if(error.response.status === 401){
                alert("Unauthorized")
              window.location.href = '/login';
          }
        })
        this.closeDelete()
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      save () {
        this.editedItem.status = this.editedItem.status === this.status[0] ? true : false;
        const token = window.localStorage.getItem('token');
        if (this.editedIndex > -1) {

          axios.put('api/table/' + this.editedItem.id, this.editedItem, {headers: {"Authorization" : "Bearer " + token}})
          .then(res => {
            alert(res.data.msg)
            this.initialize()
            this.close()
          })
          .catch(error => {
            if(error.response.status === 401){
                alert("Unauthorized")
              window.location.href = '/login';
            }
            this.displayError(error)
          })
        } else {

          axios.post('api/table', this.editedItem, {headers: {"Authorization" : "Bearer " + token}})
          .then(res => {
            alert(res.data.msg)
            this.initialize()
            this.close()
          })
          .catch(error => {
            if(error.response.status === 401){
                alert("Unauthorized")
              window.location.href = '/login';
            }
            this.displayError(error)
          })
        }
      },
    },
  }
</script>
<style scoped>
  .v-application .error{
    color: red !important; 
    font-size:0.8rem;
    background-color: white !important;
  }
</style>