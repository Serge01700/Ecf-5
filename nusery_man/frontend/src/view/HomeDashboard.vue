<template>
  <div class="home-dashboard">
    
    <StatOverview />
    <div class="flex">
          <EventHome
        :events="myEvents"
        @event-click="handleEventClick"
        />
        <MealComp class="mealcomp-fullwidth" />
    </div>
    

    
  </div>
</template>

<script setup>
import StatOverview from '../components/StatOverview.vue';
import EventHome from '../components/EventHome.vue';
import MealComp from '../components/MealComp.vue';

const myEvents = [
  { id: 1, type: 'reading', title: 'Mon événement', date: '25 juin' },
  {
        id: 2,
        type: 'animals',
        title: 'Semaine des animaux',
        date: 'Début septembre'
      },
      {
        id: 3,
        type: 'music',
        title: 'Fête de la musique',
        date: '21 juin'
      }
]


</script>

<style scoped>
.flex {
  display: flex;
  gap:15px;
}

.mealcomp-fullwidth {
  width: 100%;
  /* margin-top: 1rem; */
  height: 100%;
}


.home-dashboard {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap:1.5rem;
}
</style>