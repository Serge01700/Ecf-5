<template>
 <router-view></router-view>
  
</template>

<script setup>
import { RouterView } from 'vue-router'
</script>

<style>

@font-face {
  font-family: 'GT';
  src: url('/fonts/GT-Walsheim.otf') format('opentype');
  font-weight: normal;
  font-style: normal;
}

* {
  font-family: 'GT', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
</style>