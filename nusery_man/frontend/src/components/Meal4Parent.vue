<template>
  <div class="meal-planner">
    <header class="meal-header">
      <div class="meal-title">
        <h1>Repas du jour</h1>
        <input 
          type="date" 
          v-model="selectedDate" 
          @change="loadMeals"
          class="date-picker"
        />
      </div>
    </header>

    <div class="meal-grid">
      <!-- Matinée -->
      <div class="meal-section">
        <h2>Matinée</h2>
        <div class="meal-items">
          <div 
            v-for="meal in meals.matinee" 
            :key="meal.id"
            class="meal-item"
          >
            <h3>{{ meal.name }}</h3>
            <p v-if="meal.description">{{ meal.description }}</p>
            <span v-if="meal.ageGroup" class="age-group">({{ meal.ageGroup }})</span>
          </div>
          <div v-if="meals.matinee.length === 0" class="empty-state">
            Aucun repas prévu
          </div>
        </div>
      </div>

      <!-- Déjeuner -->
      <div class="meal-section">
        <h2>Déjeuner</h2>
        <div class="meal-items">
          <div 
            v-for="meal in meals.dejeuner" 
            :key="meal.id"
            class="meal-item"
          >
            <h3>{{ meal.name }}</h3>
            <p v-if="meal.description">{{ meal.description }}</p>
            <span v-if="meal.ageGroup" class="age-group">({{ meal.ageGroup }})</span>
          </div>
          <div v-if="meals.dejeuner.length === 0" class="empty-state">
            Aucun repas prévu
          </div>
        </div>
      </div>

      <!-- Goûter -->
      <div class="meal-section">
        <h2>Goûter</h2>
        <div class="meal-items">
          <div 
            v-for="meal in meals.gouter" 
            :key="meal.id"
            class="meal-item"
          >
            <h3>{{ meal.name }}</h3>
            <p v-if="meal.description">{{ meal.description }}</p>
            <span v-if="meal.ageGroup" class="age-group">({{ meal.ageGroup }})</span>
          </div>
          <div v-if="meals.gouter.length === 0" class="empty-state">
            Aucun repas prévu
          </div>
        </div>
      </div>
    </div>

    <div v-if="loading" class="loading">
      Chargement...
    </div>
  </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue'

export default {
  name: 'MealViewer',
  setup() {
    const meals = reactive({
      matinee: [],
      dejeuner: [],
      gouter: []
    })

    const selectedDate = ref(new Date().toISOString().split('T')[0])
    const loading = ref(false)

    const loadMeals = async () => {
      loading.value = true
      try {
        const response = await fetch(`/api/meals?date=${selectedDate.value}`)
        const data = await response.json()
        meals.matinee = data.matinee || []
        meals.dejeuner = data.dejeuner || []
        meals.gouter = data.gouter || []
      } catch (error) {
        console.error('Erreur lors du chargement des repas:', error)
        alert('Erreur lors du chargement des repas')
      } finally {
        loading.value = false
      }
    }

    onMounted(() => {
      loadMeals()
    })

    return {
      meals,
      selectedDate,
      loading,
      loadMeals
    }
  }
}
</script>

<style scoped>
.meal-planner {
  padding: 20px;
  background-color: white;
  border-radius: 8px;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.meal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding: 20px;
  border-radius: 12px;
}

.meal-title h1 {
  margin: 0 0 10px 0;
  color: #333;
  font-size: 1.5rem;
}

.date-picker {
  padding: 8px 12px;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 1rem;
}

.meal-grid {
  display: grid;
  grid-template-columns: repeat(3,1fr);
  gap: 10px;
}

.meal-section {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.meal-section h2 {
  margin: 0 0 20px 0;
  color: #495057;
  font-size: 1rem;
  border-bottom: 2px solid #e9ecef;
  padding-bottom: 10px;
}

.meal-items {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.meal-item {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 8px;
  transition: all 0.2s;
  border: 2px solid transparent;
}

.meal-item h3 {
  margin: 0 0 8px 0;
  color: #333;
  font-size: 1rem;
}

.meal-item p {
  margin: 0 0 8px 0;
  color: #666;
  font-size: 0.9rem;
}

.age-group {
  font-style: italic;
  color: #6c757d;
  font-size: 0.85rem;
}

.empty-state {
  text-align: center;
  color: #6c757d;
  font-style: italic;
  padding: 40px 20px;
}

.loading {
  text-align: center;
  padding: 40px;
  color: #6c757d;
  font-size: 1.2rem;
}

@media (max-width: 768px) {
  .meal-header {
    flex-direction: column;
    gap: 15px;
    text-align: center;
  }
  
  .meal-grid {
    grid-template-columns: 1fr;
  }
}
</style>