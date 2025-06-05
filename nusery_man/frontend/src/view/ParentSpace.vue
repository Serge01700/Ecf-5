<template>
<div class="parent-space">
    <ChildForm/>

<div class="event">
    <EventHome />
</div>
<div class="presence">
    <PresenceCalendar />
</div>

<div class="meal-today">
    <Meal4Parent />
</div>
</div>




</template>



<script setup>
import ChildForm  from '../components/ChildForm.vue';
import EventHome from '../components/EventHome.vue';
import PresenceCalendar from '../components/PresenceCalendar.vue';
import Meal4Parent from '../components/Meal4Parent.vue';

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