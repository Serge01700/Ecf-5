<template>
  <div class="home-dashboard">
    
    <StatOverview />
    <EventHome
    :events="myEvents"
    @event-click="handleEventClick"
    />

    
  </div>
</template>

<script setup>
import StatOverview from '../components/StatOverview.vue';
import EventHome from '../components/EventHome.vue';

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
.home-dashboard {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap:1.5rem;
}
</style>