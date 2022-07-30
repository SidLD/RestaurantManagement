<template>
  <v-app id="inspire">
    <v-navigation-drawer
        class="drawer"    
        color="blue"
        dense
        rounded
        v-model="drawer"
        width="200"
        app
    >
    <v-list flat>
      <v-subheader>
        <img
          class="img"
          height="50"
          width="50"
          v-bind:src="getImage('restaurant-logo.png')"
        />
      </v-subheader>
      <v-list-item-group
        v-model="selectedItem"
        color="green"
      >
        <v-list-item
          v-for="(link, i) in links"
          :key="i"
        >
        <v-btn color="white" text @click="shareData(link.text)" >
              <v-list-item-icon>
            <v-icon v-text="link.icon"></v-icon>
          </v-list-item-icon>
            <v-list-item-content>
                <v-list-item-title class="link-text" v-text="link.text"></v-list-item-title>
        </v-list-item-content>
        </v-btn>
        </v-list-item>
      </v-list-item-group>
    </v-list>

    </v-navigation-drawer>

    <v-app-bar app
        color="blue"
        dense
        elevation="0"
        outlined
    >
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title>Reservation Restaurant</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click="logout">
            <v-icon size="30">mdi-logout</v-icon>
        </v-btn>
    </v-app-bar>

    <v-main>
      <router-view/>
    </v-main>
  </v-app>
</template>

<script>
import { mdiAccountGroup, 
        mdiAccountGroupOutline, 
        mdiAccountSupervisorCircle, 
        mdiFood,
        mdiFoodVariant,
        mdiFoodForkDrink,
        mdiFoodTurkey,
        mdiReceiptTextCheck} from '@mdi/js';
  export default {
    data: function() {
      return {
          drawer : null,
          logo : "restaurant-logo.png",
          selectedItem: 1,
          links : [
            {
              link : "bookings",
              icon : mdiReceiptTextCheck,
              text : "Booking"
            },
            {
              link : "products",
              icon : mdiFood,
              text : "Product"
            },
            {
              link : "users",
              icon : mdiAccountGroup,
              text : "User"
            },
            {
              link : "employees",
              icon : mdiAccountGroupOutline,
              text : "Employee"
            },
            {
              link : "meals",
              icon : mdiFoodTurkey,
              text : "Meal"
            },
            {
              link : "categories",
              icon : mdiFoodVariant,
              text : "Category"
            },
            {
              link : "tables",
              icon : mdiFoodForkDrink,
              text : "Table"
            },
            {
              link : "admins",
              icon : mdiAccountSupervisorCircle,
              text : "Admin"
            },
          ],
      }
    },
    methods:{
      getImage(filename){
            return "./storage/images/" + filename;
      },
      logout(){
         window.localStorage.setItem('token', null);
         window.localStorage.setItem('isLogin', false);
         
         window.location.href = "/login";
      },
      shareData(route){
        this.$router.push("/"+route.toLowerCase()).catch(()=>{});
      }
    }
  }
</script>

<style scoped>
  .sidebar-btn-wrapper{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  .img{
    display: flex;
    margin: auto;
  }
</style>