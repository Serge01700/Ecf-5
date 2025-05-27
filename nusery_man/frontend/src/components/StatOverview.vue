<template>
  <div class="stats-overview">
   
    <StatsHome
      title="Nombre total d'enfants"
      :value="28"
      change="+5%"
      compareText="comparé à l'année dernière"
      :isPositive="true"
    >
      
    </StatsHome>

    <StatsHome
      title="Nombre d'enfants aujourd'hui"
      :value="25"
      change="+25%"
      compareText="comparé à la semaine dernière"
      :isPositive="true"
    >
      
    </StatsHome>

    <StatsHome
      title="Nombre de personnel aujourd'hui"
      :value="15"
      change="+1%"
      compareText="comparé à la semaine dernière"
      :isPositive="true"
    >
     
    </StatsHome>
  </div>
</template>

<script setup>
import StatsHome from './StatsHome.vue';

</script>

<style scoped>
.stats-overview {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1rem;
}
</style>
