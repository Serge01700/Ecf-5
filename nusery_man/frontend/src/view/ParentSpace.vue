<template>
<div class="parent-space">
    <ChildForm/>

<div class="event">
    <EventHome />
</div>
</div>




</template>



<script setup>
import ChildForm  from '../components/ChildForm.vue';
import EventHome from '../components/EventHome.vue';

</script>



<style>

.img-child{
    height: 50px;
    width: 50px;
    background-color: rgb(149, 146, 146);
    border-radius: 50%;
}
.parent-space {
      padding: 1rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

</style>