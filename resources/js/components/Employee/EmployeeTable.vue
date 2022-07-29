<template>
  <v-data-table
    :headers="headers"
    :items="users"
    sort-by="id"
  >
    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>Employee Table</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        >
        </v-divider>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="800px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="primary"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
              rounded
            >
              New Employee
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="text-h5">New Employee</span>
            </v-card-title>

            <v-card-text class="container-fluid">
              <v-container class="container-fluid">
                <v-row>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.first_name"
                            label="First Name"
                        ></v-text-field>
                        <div :v-if="errorFirstName">
                          <label class="error" v-for="error,index in errorFirstName" :key="index"> 
                          {{error}}*
                          </label>
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.last_name"
                            label="Last Name"
                        ></v-text-field>
                        <div :v-if="errorLastName">
                          <label class="error" v-for="error,index in errorLastName" :key="index"> 
                          {{error}}*
                          </label>
                        </div>
                    </v-col>
                    <v-col cols="12" sm="4" md="4">
                        <v-text-field v-model="editedItem.contact"
                            label="Contact"
                        ></v-text-field>
                        <div :v-if="errorContact">
                          <label class="error" v-for="error,index in errorContact" :key="index"> 
                          {{error}}*
                          </label>
                        </div>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                        <v-text-field v-model="editedItem.email"
                            label="Email"
                        ></v-text-field>
                        <div :v-if="errorEmail">
                          <span class="error" v-for="error,index in errorEmail" :key="index"> 
                          {{error}}*
                          </span>
                        </div>
                    </v-col>
                    <v-col v-if="!editedItem.id" cols="12" sm="6" md="6">
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
                            label="Picker without buttons"
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
          <v-btn   
            color="primary"
            elevation="2"
            rounded
            x-large @click="showPasswordField = !showPasswordField">Show Password Field
            </v-btn>
              <v-col cols="12" sm="6" md="12">
                <v-text-field  v-if="!editedItem.id && showPasswordField" v-model="editedItem.password" 
                  label="Set Password"
                  type="password"
                ></v-text-field>
                <v-text-field v-if="editedItem.id && showPasswordField" v-model="editedItem.password" 
                    label="Change Password"
                    type="password"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="12">
                  <v-text-field v-if="showPasswordField && showPasswordField" v-model="editedItem.confirmPassword" 
                      label="Confirm  Password"
                      type="password"
                  ></v-text-field>
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
              <v-btn large color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
              <v-btn large color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon
        medium
        color="yellow dark"
        class="mr-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        color="red"
        medium
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
      showPasswordField : false,
      menu: false,
      modal: false,
      menu2: false,
      date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
      dialog: false,
      dialogDelete: false,
      headers: [
        {
          text: 'ID',
          align: 'start',
          sortable: false,
          value: 'id',
        },
        { text: 'First Name', value: 'first_name' },
        { text: 'Last Name', value: 'last_name' },
        { text: 'Email', value: 'email' },
        { text: 'Contact', value: 'contact' },
        { text: 'Created Date', value: 'created_at'},
        { text: 'Actions', value: 'actions', sortable: false },
      ],
        users: [],
        errorFirstName : null,
        errorLastName : null,
        errorEmail : null,
        errorContact : null,
        editedIndex: -1,
        editedItem: {
          first_name: '',
          last_name: '',
          email: '',
          contact: '',
          password: '',
          created_at: "",
          confirmPassword: ''
        },
        defaultItem: {
          first_name: '',
          last_name: '',
          email: '',
          contact: '',
          created_at: '',
          password: '',
          confirmPassword: ''
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
        axios.get('api/user/employee', {headers: {"Authorization" : "Bearer " + token}})
        .then(reponse => {
          this.users = reponse.data;
        }).catch(error => {
          if(error.response.status === 401){
                alert("Unauthorized")
                window.location.href = '/login';
          }
        })
      },

      editItem (item) {
        this.editedIndex = this.users.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        this.editedIndex = this.users.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
      },

      deleteItemConfirm () {
        const token = window.localStorage.getItem('token');
        axios.delete('api/user/employee/' + this.editedItem.id, {headers: {"Authorization" : "Bearer " + token}})
          .then(res  => {
             if(res.data.res === "ok"){
               alert("Success")
             }else {
               alert(res.data.msg);
             }
          }).catch(error => {
          if(error.response.status === 401){
                alert("Unauthorized")
              window.location.href = '/login';
          }
        })
        this.initialize();
        this.closeDelete();
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
      resetError(){
        this.errorFirstName = null;
        this.errorLastName = null;
        this.errorContact = null;
        this.errorEmail = null;
      },
      displayError(error){
        if(error.response.data.errors.firstName){
          this.errorFirstName = error.response.data.errors.firstName;
        }
        if(error.response.data.errors.lastName){
          this.errorLastName = error.response.data.errors.lastName;
        }
        if(error.response.data.errors.email){
          this.errorEmail = error.response.data.errors.email;
        }
        if(error.response.data.errors.contact){
          this.errorContact = error.response.data.errors.contact;
        }
      },
      save () {
        if (this.editedItem.id) {
          //Edit User and check if new password or not
          let editedUser;
          if(this.password != "" && this.confirmPassword != ""){
            editedUser = {
                firstName : this.editedItem.first_name,
                lastName : this.editedItem.last_name,
                contact : this.editedItem.contact,
                email : this.editedItem.email,
                password : this.editedItem.password,
                confirmPassword : this.editedItem.confirmPassword
            }
          }else{
            editedUser = {
              firstName : this.editedItem.first_name,
              lastName : this.editedItem.last_name,
              contact : this.editedItem.contact,
              email : this.editedItem.email,
            }
          }
          const token = window.localStorage.getItem('token');
            axios.put('api/user/employee/' + this.editedItem.id, editedUser, {headers: {"Authorization" : "Bearer " + token}})
            .then(res  => {
              if(res.data.res === "ok"){
                alert("Success")
                this.resetError()
                this.showPasswordField = false,
                this.close()
                this.initialize();
              }else {
                alert(res.data.msg);
              }
            })
            .catch(error => {
                if(error.response.status === 401){
                  alert("Unauthorized")
                window.location.href = '/login';
              }
              this.displayError(error)
            })
        } else {
            //Create User via API
            let editedUser = {
                firstName : this.editedItem.first_name,
                lastName : this.editedItem.last_name,
                contact : this.editedItem.contact,
                email : this.editedItem.email,
                created_at : this.date,
                password : this.editedItem.password,
                confirmPassword : this.editedItem.confirmPassword
            }
            const token = window.localStorage.getItem('token');
             axios.post('api/user/employee', editedUser, {headers: {"Authorization" : "Bearer " + token}})
             .then(res  => {
                if(res.data.res === "ok"){
                  alert("Success");
                  this.resetError();
                  this.showPasswordField = false,
                  this.close()
                  this.initialize();
                }else {
                  alert(res.data.msg);
                }
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