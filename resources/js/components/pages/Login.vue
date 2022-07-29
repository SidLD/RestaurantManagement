<template>
   <v-app id="inspire">
      <v-main>
         <v-container fluid fill-height>
            <v-layout align-center justify-center>
               <v-flex xs12 sm8 md4>
                  <v-card class="elevation-12">
                     <v-toolbar dark color="primary">
                        <v-toolbar-title>Login form</v-toolbar-title>
                     </v-toolbar>
                     <v-card-text>
                        <v-form>
                          <v-row>
                            <v-col cols="2">
                              <v-icon>{{icons.mdiAccount}}</v-icon>
                            </v-col>
                            <v-col cols="10">
                            <v-text-field
                               label="Email"
                               type="email"
                               v-model="email"
                            ></v-text-field>
                            </v-col>
                            <v-col cols="2">
                              <v-icon >{{icons.mdiKeyVariant}}</v-icon>
                            </v-col>
                            <v-col cols="10">
                              <v-text-field
                               label="Password"
                               type="password"
                               v-model="password"
                            ></v-text-field>
                            </v-col>
                          </v-row>
                        </v-form>
                     </v-card-text>
                     <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="login">Login</v-btn>
                     </v-card-actions>
                  </v-card>
               </v-flex>
            </v-layout>
         </v-container>
      </v-main>
   </v-app>
</template>
<script>

import { mdiAccount, mdiKeyVariant } from '@mdi/js';
export default {
  data(){
    return{
      email:"",
      password:"",
      icons: {
        mdiAccount,
        mdiKeyVariant
      },
    }
  },
  methods:{
    login(){
      const login = {
         email : this.email,
         password: this.password
      }
      axios.post('api/login', login)
      .then(res => {
         window.localStorage.setItem('token', res.data.token);
         window.localStorage.setItem('isLogin', 'true');
         window.location.href = "/booking";
      })
      .catch(error => {
         console.log("error" + error.response.status)
         alert("Incorrect Email or Password")
      })
    }
  },
  created(){
   // if(window.localStorage.getItem('isLogin')){
   //    window.location.href = "/booking";
   // }
  }
}
</script>
<style scoped>
</style>