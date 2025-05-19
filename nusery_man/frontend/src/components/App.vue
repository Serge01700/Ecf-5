<template>
  <DashboardLayout>
    <div>
    <h1>{{ message }}</h1>
  </div>
  </DashboardLayout>

</template>

<script setup>
import { ref } from 'vue'
import  DashboardLayout from '../layout/DashboardLayout.vue'

const message = ref('Hoo')
</script>

<style>

</style>