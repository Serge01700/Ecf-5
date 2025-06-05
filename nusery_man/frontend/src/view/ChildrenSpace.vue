<template>
  <div class="parent-dashboard">
    <!-- Header -->
    <header class="dashboard-header">
      <div class="date-circle">
        <div class="day">13</div>
        <div class="date-text">
          <div>Tuesday,</div>
          <div>April</div>
        </div>
      </div>
      
      <div class="header-spacer"></div>
      
      <div class="user-avatar">
        <svg viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
      </div>
    </header>

    <!-- Section des enfants -->
    <section class="children-section">
      <div class="section-controls">
        <div class="search-container">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Rechercher un enfant ..." 
            class="search-input"
          >
        </div>
        
        <button class="add-button" @click="showAddChildModal = true">
          Ajouter
        </button>
        
        <div class="group-filter">
          <select v-model="selectedGroup" class="group-select">
            <option value="">Grands</option>
            <option value="petits">Petits</option>
            <option value="moyens">Moyens</option>
          </select>
        </div>
      </div>

      <!-- Tableau des enfants -->
      <div class="children-table-container">
        <table class="children-table">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Date de naissance</th>
              <th>Allergies</th>
              <th>Groupe</th>
              <th>Présence</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="child in filteredChildren" :key="child.id" class="child-row">
              <td class="child-name">{{ child.name }}</td>
              <td>{{ child.birthDate }}</td>
              <td>{{ child.allergies }}</td>
              <td>{{ child.group }}</td>
              <td>
                <span 
                  class="presence-badge"
                  :class="{ 'present': child.isPresent, 'absent': !child.isPresent }"
                >
                  {{ child.isPresent ? 'Présent' : 'Absent' }}
                </span>
              </td>
              <td>
                <button 
                  class="action-button view-button" 
                  @click="viewChild(child)"
                >
                  Voir
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Section du planning -->
    <section class="schedule-section">
      <div class="schedule-header">
        <div class="schedule-title">
          <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
          </svg>
          <span>Semaine 20</span>
        </div>
        
        <div class="week-navigation">
          <button @click="previousWeek" class="nav-button">‹</button>
          <span class="week-dates">13 - 19 mai</span>
          <button @click="nextWeek" class="nav-button">›</button>
        </div>
      </div>

      <div class="schedule-search">
        <svg viewBox="0 0 24 24" fill="currentColor">
          <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
        </svg>
        <input 
          v-model="scheduleSearch" 
          type="text" 
          placeholder="Rechercher un enfant ..." 
          class="schedule-search-input"
        >
      </div>

      <div class="schedule-controls">
        <h3>Tableau des présences hebdomadaire :</h3>
        <button class="filter-button" @click="showFilters = !showFilters">
          Filtrer
        </button>
      </div>

      <!-- Tableau des présences -->
      <div class="attendance-table-container">
        <table class="attendance-table">
          <thead>
            <tr>
              <th class="child-column">Enfant</th>
              <th>Lundi</th>
              <th>Mardi</th>
              <th>Mercredi</th>
              <th>Jeudi</th>
              <th>Vendredi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="attendance in weeklyAttendance" :key="attendance.childId">
              <td class="child-name">{{ attendance.childName }}</td>
              <td v-for="day in weekDays" :key="day" class="attendance-cell">
                <div 
                  class="attendance-status"
                  :class="getAttendanceClass(attendance.schedule[day])"
                  @click="toggleAttendance(attendance.childId, day)"
                >
                  <svg viewBox="0 0 24 24" fill="currentColor">
                    <path v-if="attendance.schedule[day]" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    <path v-else d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                  </svg>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Modal d'ajout d'enfant -->
    <div v-if="showAddChildModal" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <h3>Ajouter un enfant</h3>
        <form @submit.prevent="addChild">
          <div class="form-group">
            <label>Nom :</label>
            <input v-model="newChild.name" type="text" required>
          </div>
          <div class="form-group">
            <label>Date de naissance :</label>
            <input v-model="newChild.birthDate" type="date" required>
          </div>
          <div class="form-group">
            <label>Allergies :</label>
            <input v-model="newChild.allergies" type="text">
          </div>
          <div class="form-group">
            <label>Groupe :</label>
            <select v-model="newChild.group" required>
              <option value="Grands">Grands</option>
              <option value="Moyens">Moyens</option>
              <option value="Petits">Petits</option>
            </select>
          </div>
          <div class="modal-actions">
            <button type="button" @click="closeModal" class="cancel-button">Annuler</button>
            <button type="submit" class="submit-button">Ajouter</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive } from 'vue'

// États réactifs
const searchQuery = ref('')
const selectedGroup = ref('')
const scheduleSearch = ref('')
const showFilters = ref(false)
const showAddChildModal = ref(false)
const currentWeek = ref(20)


const children = ref([
  {
    id: 1,
    name: 'Nina Duval',
    birthDate: '2023-01-03',
    allergies: 'Lait, gluten',
    group: 'Grands',
    isPresent: true
  }
])

// Nouveau formulaire enfant
const newChild = reactive({
  name: '',
  birthDate: '',
  allergies: '',
  group: 'Grands'
})

// Données de présences hebdomadaires
const weekDays = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi']
const weeklyAttendance = ref([
  {
    childId: 1,
    childName: 'Nina Duval',
    schedule: {
      lundi: true,
      mardi: false,
      mercredi: false,
      jeudi: true,
      vendredi: true
    }
  }
])

const filteredChildren = computed(() => {
  return children.value.filter(child => {
    const matchesSearch = child.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesGroup = !selectedGroup.value || child.group === selectedGroup.value
    return matchesSearch && matchesGroup
  })
})


const viewChild = (child) => {
  console.log('Voir enfant:', child)
  
}

const addChild = () => {
  const child = {
    id: Date.now(),
    name: newChild.name,
    birthDate: newChild.birthDate,
    allergies: newChild.allergies || 'Aucune',
    group: newChild.group,
    isPresent: false
  }
  
  children.value.push(child)
  
  // Ajouter aux présences hebdomadaires
  weeklyAttendance.value.push({
    childId: child.id,
    childName: child.name,
    schedule: {
      lundi: false,
      mardi: false,
      mercredi: false,
      jeudi: false,
      vendredi: false
    }
  })
  
  closeModal()
}

const closeModal = () => {
  showAddChildModal.value = false
  Object.assign(newChild, {
    name: '',
    birthDate: '',
    allergies: '',
    group: 'Grands'
  })
}

const toggleAttendance = (childId, day) => {
  const attendance = weeklyAttendance.value.find(a => a.childId === childId)
  if (attendance) {
    attendance.schedule[day] = !attendance.schedule[day]
  }
}

const getAttendanceClass = (isPresent) => {
  return isPresent ? 'present' : 'absent'
}

const previousWeek = () => {
  currentWeek.value--
}

const nextWeek = () => {
  currentWeek.value++
}
</script>

<style scoped>
.parent-dashboard {
  min-height: 100vh;
  background-color: #f0f0f0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Header */
.dashboard-header {
  display: flex;
  align-items: center;
  padding: 16px 24px;
  background: white;
  border-bottom: 1px solid #e9ecef;
}

.date-circle {
  display: flex;
  align-items: center;
  gap: 12px;
}

.day {
  width: 48px;
  height: 48px;
  background-color: #6c757d;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  font-weight: bold;
}

.date-text {
  font-size: 14px;
  color: #6c757d;
}

.header-spacer {
  flex: 1;
}

.user-avatar {
  width: 40px;
  height: 40px;
  background-color: #6c757d;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.user-avatar svg {
  width: 20px;
  height: 20px;
}

/* Section des enfants */
.children-section {
  padding: 24px;
  background: white;
  margin: 16px 24px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.section-controls {
  display: flex;
  gap: 16px;
  margin-bottom: 24px;
  align-items: center;
}

.search-container {
  flex: 1;
  max-width: 300px;
}

.search-input {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  font-size: 14px;
}

.add-button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

.add-button:hover {
  background-color: #0056b3;
}

.group-select {
  padding: 8px 12px;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  font-size: 14px;
}

/* Tableau des enfants */
.children-table-container {
  overflow-x: auto;
}

.children-table {
  width: 100%;
  border-collapse: collapse;
}

.children-table th,
.children-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #dee2e6;
}

.children-table th {
  background-color: #f8f9fa;
  font-weight: 500;
  color: #495057;
}

.child-name {
  font-weight: 500;
}

.presence-badge {
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
}

.presence-badge.present {
  background-color: #d4edda;
  color: #155724;
}

.presence-badge.absent {
  background-color: #f8d7da;
  color: #721c24;
}

.action-button {
  padding: 6px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
}

.view-button {
  background-color: #007bff;
  color: white;
}

.view-button:hover {
  background-color: #0056b3;
}

/* Section planning */
.schedule-section {
  padding: 24px;
  background: white;
  margin: 16px 24px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.schedule-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.schedule-title {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 500;
}

.schedule-title svg {
  width: 20px;
  height: 20px;
}

.week-navigation {
  display: flex;
  align-items: center;
  gap: 16px;
}

.nav-button {
  background: none;
  border: none;
  font-size: 18px;
  cursor: pointer;
  padding: 4px 8px;
}

.schedule-search {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 16px;
  padding: 8px 12px;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  max-width: 300px;
}

.schedule-search svg {
  width: 16px;
  height: 16px;
  color: #6c757d;
}

.schedule-search-input {
  border: none;
  outline: none;
  flex: 1;
  font-size: 14px;
}

.schedule-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.filter-button {
  background: none;
  border: 1px solid #dee2e6;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

/* Tableau des présences */
.attendance-table-container {
  overflow-x: auto;
}

.attendance-table {
  width: 100%;
  border-collapse: collapse;
}

.attendance-table th,
.attendance-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #dee2e6;
}

.attendance-table th {
  background-color: #f8f9fa;
  font-weight: 500;
  color: #495057;
}

.child-column {
  min-width: 150px;
}

.attendance-cell {
  text-align: center;
}

.attendance-status {
  width: 32px;
  height: 32px;
  border-radius: 4px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
}

.attendance-status.present {
  background-color: #28a745;
  color: white;
}

.attendance-status.absent {
  background-color: #dc3545;
  color: white;
}

.attendance-status svg {
  width: 16px;
  height: 16px;
}


.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 24px;
  border-radius: 8px;
  max-width: 500px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
}

.form-group {
  margin-bottom: 16px;
}

.form-group label {
  display: block;
  margin-bottom: 4px;
  font-weight: 500;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  font-size: 14px;
}

.modal-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  margin-top: 24px;
}

.cancel-button {
  padding: 8px 16px;
  border: 1px solid #dee2e6;
  background: white;
  border-radius: 4px;
  cursor: pointer;
}

.submit-button {
  padding: 8px 16px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.submit-button:hover {
  background-color: #0056b3;
}

/* Responsive */
@media (max-width: 768px) {
  .section-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .schedule-header {
    flex-direction: column;
    gap: 16px;
    align-items: flex-start;
  }
  
  .schedule-controls {
    flex-direction: column;
    gap: 12px;
    align-items: flex-start;
  }
}
</style>