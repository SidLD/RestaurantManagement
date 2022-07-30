<template>
  <EmployeeTable :type="type" :title="title"/>
</template>
<script>
import EmployeeTable from '../UserTable.vue'
export default {
  components: {
    EmployeeTable
  },
  data() {
    return {
      type : 'employee',
      title: 'Employee'
    }
  }
}
</script>