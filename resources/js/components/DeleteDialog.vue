<template>
  <div>
      <v-dialog v-model="dialogDelete" max-width="500px">
        <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="red"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
              rounded
            >
            <v-icon left>
            mdi-delete
          </v-icon>
              Delete
            </v-btn>
          </template>
          <v-card>
            <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="dialogDelete = false">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="deleteConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
  </div>
</template>
<script>
export default {
  props : ['type', 'id'],
  data(){
    return {
      dialogDelete : false,
    }
  },
  methods: {
    deleteConfirm(){
      const token = window.localStorage.getItem('token');
      axios.delete('api/'+this.type + "/"+this.id, {headers: {"Authorization" : "Bearer " + token}})
      .then(res => {
        alert(res.data.msg)
        this.dialogDelete = false
        this.$emit('update');
      })
      .catch(error => {
          if(error.response.status === 401){
                alert("Unauthorized")
            window.location.href = '/login';
          }
        })

    }
  },
}

</script>