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
      <button @click="showAddModal = true" class="add-button">
        Ajouter un repas
      </button>
    </header>

    <!-- Grille des repas -->
    <div class="meal-grid">
      <!-- Matinée -->
      <div class="meal-section">
        <h2>Matinée</h2>
        <div class="meal-items">
          <div 
            v-for="meal in meals.matinee" 
            :key="meal.id"
            class="meal-item"
            @click="editMeal(meal)"
          >
            <h3>{{ meal.name }}</h3>
            <p v-if="meal.description">{{ meal.description }}</p>
            <span v-if="meal.ageGroup" class="age-group">({{ meal.ageGroup }})</span>
            <button @click.stop="deleteMeal(meal.id)" class="delete-btn">×</button>
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
            @click="editMeal(meal)"
          >
            <h3>{{ meal.name }}</h3>
            <p v-if="meal.description">{{ meal.description }}</p>
            <span v-if="meal.ageGroup" class="age-group">({{ meal.ageGroup }})</span>
            <button @click.stop="deleteMeal(meal.id)" class="delete-btn">×</button>
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
            @click="editMeal(meal)"
          >
            <h3>{{ meal.name }}</h3>
            <p v-if="meal.description">{{ meal.description }}</p>
            <span v-if="meal.ageGroup" class="age-group">({{ meal.ageGroup }})</span>
            <button @click.stop="deleteMeal(meal.id)" class="delete-btn">×</button>
          </div>
          <div v-if="meals.gouter.length === 0" class="empty-state">
            Aucun repas prévu
          </div>
        </div>
      </div>
    </div>

    <!-- Modal d'ajout/édition -->
    <div v-if="showAddModal || editingMeal" class="modal-overlay" @click="closeModal">
      <div class="modal" @click.stop>
        <h3>{{ editingMeal ? 'Modifier' : 'Ajouter' }} un repas</h3>
        <form @submit.prevent="saveMeal">
          <div class="form-group">
            <label>Type de repas</label>
            <select v-model="mealForm.type" required>
              <option value="matinee">Matinée</option>
              <option value="dejeuner">Déjeuner</option>
              <option value="gouter">Goûter</option>
            </select>
          </div>

          <div class="form-group">
            <label>Nom du plat</label>
            <input 
              type="text" 
              v-model="mealForm.name" 
              required
              placeholder="Ex: Compote de pomme"
            />
          </div>

          <div class="form-group">
            <label>Description</label>
            <textarea 
              v-model="mealForm.description"
              placeholder="Description optionnelle"
              rows="2"
            ></textarea>
          </div>

          <div class="form-group">
            <label>Groupe d'âge</label>
            <select v-model="mealForm.ageGroup">
              <option value="">Tous les âges</option>
              <option value="selon âge">Selon âge</option>
            </select>
          </div>

          <div class="form-group">
            <label>Date</label>
            <input 
              type="date" 
              v-model="mealForm.date" 
              required
            />
          </div>

          <div class="form-actions">
            <button type="button" @click="closeModal" class="cancel-btn">
              Annuler
            </button>
            <button type="submit" class="save-btn">
              {{ editingMeal ? 'Modifier' : 'Ajouter' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Loading state -->
    <div v-if="loading" class="loading">
      Chargement...
    </div>
  </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue'

export default {
  name: 'MealPlanner',
  setup() {
    const meals = reactive({
      matinee: [],
      dejeuner: [],
      gouter: []
    })

    const selectedDate = ref(new Date().toISOString().split('T')[0])
    const showAddModal = ref(false)
    const editingMeal = ref(null)
    const loading = ref(false)

    const mealForm = reactive({
      type: 'matinee',
      name: '',
      description: '',
      ageGroup: '',
      date: selectedDate.value
    })

    const resetForm = () => {
      mealForm.type = 'matinee'
      mealForm.name = ''
      mealForm.description = ''
      mealForm.ageGroup = ''
      mealForm.date = selectedDate.value
    }

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

    const saveMeal = async () => {
      try {
        const method = editingMeal.value ? 'PUT' : 'POST'
        const url = editingMeal.value 
          ? `/api/meals/${editingMeal.value.id}`
          : '/api/meals'

        const response = await fetch(url, {
          method,
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(mealForm)
        })

        if (response.ok) {
          await loadMeals()
          closeModal()
        } else {
          throw new Error('Erreur lors de la sauvegarde')
        }
      } catch (error) {
        console.error('Erreur:', error)
        alert('Erreur lors de la sauvegarde')
      }
    }

    const editMeal = (meal) => {
      editingMeal.value = meal
      mealForm.type = meal.type
      mealForm.name = meal.name
      mealForm.description = meal.description || ''
      mealForm.ageGroup = meal.ageGroup || ''
      mealForm.date = meal.date
    }

    const deleteMeal = async (id) => {
      if (!confirm('Êtes-vous sûr de vouloir supprimer ce repas ?')) {
        return
      }

      try {
        const response = await fetch(`/api/meals/${id}`, {
          method: 'DELETE'
        })

        if (response.ok) {
          await loadMeals()
        } else {
          throw new Error('Erreur lors de la suppression')
        }
      } catch (error) {
        console.error('Erreur:', error)
        alert('Erreur lors de la suppression')
      }
    }

    const closeModal = () => {
      showAddModal.value = false
      editingMeal.value = null
      resetForm()
    }

    onMounted(() => {
      loadMeals()
    })

    return {
      meals,
      selectedDate,
      showAddModal,
      editingMeal,
      loading,
      mealForm,
      loadMeals,
      saveMeal,
      editMeal,
      deleteMeal,
      closeModal
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
  /* background: #f8f9fa; */
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

.add-button {
  background: #007bff;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 0.7rem;
  transition: background 0.2s;
}

.add-button:hover {
  background: #0056b3;
}

.meal-grid {
  display: grid;
  /* grid-template-columns: repeat(auto-fit, minmax(90px, 1fr)); */
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
  position: relative;
  background: #f8f9fa;
  padding: 15px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
  border: 2px solid transparent;
}

.meal-item:hover {
  background: #e9ecef;
  border-color: #007bff;
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

.delete-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  background: #dc3545;
  color: white;
  border: none;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  cursor: pointer;
  font-size: 16px;
  line-height: 1;
}

.empty-state {
  text-align: center;
  color: #6c757d;
  font-style: italic;
  padding: 40px 20px;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal {
  background: white;
  padding: 30px;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal h3 {
  margin: 0 0 20px 0;
  color: #333;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #495057;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 10px 12px;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 1rem;
  box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #007bff;
}

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  margin-top: 30px;
}

.cancel-btn {
  background: #6c757d;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
}

.save-btn {
  background: #28a745;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
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