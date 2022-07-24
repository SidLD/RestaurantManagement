<template>
  <v-app id="inspire">
    <v-navigation-drawer
        class="drawer"    
        color="blue"
        dense
        elevation="4"
        rounded
        v-model="drawer"
        width="100"
        app
    >
    <img
      class="img"
      height="70"
      width="70"
      v-bind:src="getImage('restaurant-logo.png')"
    />

    <router-link to="/bookings">
      <div class="text-center mt-5">
        <v-btn fab color="white" large>
          <div class="sidebar-btn-wrapper">
              <v-icon aria-hidden="false" medium>
                {{icons.mdiReceiptTextCheck}}
              </v-icon>
              <span class="sidebar-text">Bookings</span>  
          </div>
        </v-btn>
      </div>
    </router-link>
    <router-link to="/users">
      <div class="text-center mt-5">
        <v-btn fab color="white" large>
          <div class="sidebar-btn-wrapper">
              <v-icon aria-hidden="false" medium>
                {{icons.mdiAccountGroup}}
              </v-icon>
              <span class="sidebar-text">Users</span>  
          </div>
        </v-btn>
      </div>
    </router-link>
    <router-link to="/employees">
      <div class="text-center mt-5">
        <v-btn fab color="white" large>
          <div class="sidebar-btn-wrapper">
              <v-icon aria-hidden="false" medium>
                {{icons.mdiAccountGroupOutline}}
              </v-icon>
              <span class="sidebar-text">Employees</span>  
          </div>
        </v-btn>
      </div>
    </router-link>
    <router-link to="/products">
      <div class="text-center mt-5">
        <v-btn fab color="white" large>
          <div class="sidebar-btn-wrapper">
              <v-icon aria-hidden="false" medium>
                {{icons.mdiFoodTurkey}}
              </v-icon>
              <span class="sidebar-text">Products</span>  
          </div>
        </v-btn>
      </div>
    </router-link>
    <router-link to="/meals">
      <div class="text-center mt-5">
        <v-btn fab color="white" large>
          <div class="sidebar-btn-wrapper">
              <v-icon aria-hidden="false" medium>
                {{icons.mdiFoodForkDrink}}
              </v-icon>
              <span class="sidebar-text">Meals</span>  
          </div>
        </v-btn>
      </div>
    </router-link>
    <router-link to="/categories">
      <div class="text-center mt-5">
        <v-btn fab color="white" large>
          <div class="sidebar-btn-wrapper">
              <v-icon aria-hidden="false" medium>
               {{icons.mdiFood}}
              </v-icon>
              <span class="sidebar-text">Categories</span>  
          </div>
        </v-btn>
      </div>
    </router-link>
    <router-link to="/admins">
      <div class="text-center mt-5">
        <v-btn fab color="white" large>
          <div class="sidebar-btn-wrapper">
              <v-icon aria-hidden="false" medium>
                {{icons.mdiAccountSupervisorCircle}}
              </v-icon>
              <span class="sidebar-text">Admins</span>  
          </div>
        </v-btn>
      </div>
    </router-link>
  
    </v-navigation-drawer>

    <v-app-bar app
        color="blue"
        dense
        elevation="4"
        outlined
        rounded
    >
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title>Reservation Restaurant</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon >
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
          icons: {
            mdiAccountGroup,
            mdiAccountGroupOutline,
            mdiAccountSupervisorCircle,
            mdiFood,
            mdiFoodTurkey,
            mdiFoodForkDrink,
            mdiFoodVariant,
            mdiReceiptTextCheck
          }
      }
    },
    methods:{
      getImage(filename){
            return "./storage/images/" + filename;
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
  .sidebar-text{
    font-size: 0.5rem;
  }
  .img{
    display: flex;
    margin: auto;
  }
</style>