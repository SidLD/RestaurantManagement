<template>
  <AdminTable :type="type" :title="title"/>
</template>
<script>
import AdminTable from '../UserTable.vue'
export default {
  components: {
    AdminTable
  },
  data() {
    return {
      type : 'admin',
      title: 'Admin'
    }
  }
}
</script>