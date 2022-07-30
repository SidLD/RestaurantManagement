<template>
  <UserTable :type="type" :title="title"/>
</template>
<script>
import UserTable from '../UserTable.vue'
export default {
  components: {
    UserTable
  },
  data() {
    return {
      type : 'user',
      title: 'User'
    }
  }
}
</script>