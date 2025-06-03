<template>
  <div class="home-dashboard">
    <StatOverview />
    <div class="layout">
      <div class="content-left">
        <EventHome
          :events="myEvents"
          @event-click="handleEventClick"
        />
        <Messagerie class="messagerie-fullwidth" />
      </div>
      <div class="content-right">
        <MealComp class="mealcomp-fullwidth" />
      </div>
    </div>
  </div>
</template>

<script setup>
import StatOverview from '../components/StatOverview.vue';
import EventHome from '../components/EventHome.vue';
import MealComp from '../components/MealComp.vue';
import Messagerie  from '../components/Messagerie.vue';

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
.layout {
  display: flex;
  gap: 15px;
  width: 100%;
}

.content-left {
  display: flex;
  flex-direction: column;
  gap: 15px;
  width: 420px; /* Ajuste cette valeur selon la largeur souhaitée */
}

.content-right {
  flex: 1;
}

.mealcomp-fullwidth,
.messagerie-fullwidth {
  width: 100%;
}

.home-dashboard {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}
</style>