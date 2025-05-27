<template>
  <div class="upcoming-events">
    <h2 class="upcoming-events__title">Événement à venir</h2>
    
    <div class="upcoming-events__list">
      <div 
        v-for="event in events" 
        :key="event.id"
        class="event-item"
        @click="onEventClick(event)"
      >
        <div class="event-item__icon">
          <component :is="getIconComponent(event.type)" />
        </div>
        
        <div class="event-item__content">
          <h3 class="event-item__title">{{ event.title }}</h3>
          <p class="event-item__date">{{ event.date }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'


const props = defineProps({
  events: {
    type: Array,
    default: () => [
      {
        id: 1,
        type: 'reading',
        title: 'Semaine de la Lecture Jeunesse',
        date: '1er au 7 juillet 2025'
      },
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
  }
})


const emit = defineEmits(['event-click'])

// Méthodes
const getIconComponent = (type) => {
  const icons = {
    reading: 'BookIcon',
    animals: 'AnimalIcon',
    music: 'MusicIcon'
  }
  return icons[type] || 'DefaultIcon'
}

const onEventClick = (event) => {
  emit('event-click', event)
}
</script>

<style scoped>
.upcoming-events {
  background-color: white;
 box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  border-radius: 8px;
  padding: 16px;
  max-width: 320px;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.upcoming-events__title {
  margin: 0 0 16px 0;
  font-size: 16px;
  font-weight: 500;
  color: #333;
}

.upcoming-events__list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.event-item {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  transition: background-color 0.2s ease;
  padding: 4px;
  border-radius: 4px;
}

.event-item:hover {
  background-color: #ebebeb;
}

.event-item__icon {
  width: 40px;
  height: 40px;
  background-color: #333;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.event-item__content {
  flex: 1;
  min-width: 0;
}

.event-item__title {
  margin: 0 0 4px 0;
  font-size: 14px;
  font-weight: 500;
  color: #333;
  line-height: 1.3;
}

.event-item__date {
  margin: 0;
  font-size: 12px;
  color: #666;
  line-height: 1.2;
}

/* Icônes SVG intégrées */
.event-item__icon svg {
  width: 20px;
  height: 20px;
  fill: white;
}
</style>

