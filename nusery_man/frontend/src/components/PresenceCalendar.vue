<template>
  <div id="app">
    <div class="container">

      <!-- Messages de notification -->
      <div v-if="message" class="alert" :class="messageType">
        {{ message }}
      </div>
 
      <!-- Informations de l'enfant -->
      <div v-if="child" class="presence-section">
        <div class="child-info">
          <h2>{{ child.firstName }} {{ child.lastName }}</h2>
          <p class="child-age">{{ getAge(child.birthDate) }} ans</p>
        </div>
      </div>

      <!-- Statistiques mensuelles -->
      <div v-if="child && monthlyStats" class="stats-section">
        <h3>Statistiques du mois</h3>
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-number">{{ monthlyStats.totalDays }}</div>
            <div class="stat-label">Jours de présence</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ monthlyStats.totalHours }}h</div>
            <div class="stat-label">Heures totales</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ monthlyStats.avgHours }}h</div>
            <div class="stat-label">Moyenne par jour</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'App',
  data() {
    return {
      child: null,
      message: '',
      messageType: 'success',
      monthlyStats: null,
      apiBaseUrl: '/api'
    }
  },
  async mounted() {
    await this.loadChildForCurrentParent()
  },
  methods: {
    async loadChildForCurrentParent() {
      try {
        const response = await axios.get(`${this.apiBaseUrl}/children/me`)
        this.child = response.data
        if (this.child?.id) {
          this.loadMonthlyStats(this.child.id)
        }
      } catch (error) {
        console.error('Erreur lors du chargement de l’enfant:', error)
        this.showMessage("Impossible de charger les informations de l'enfant", 'error')
      }
    },

    async loadMonthlyStats(childId) {
      try {
        const now = new Date()
        const response = await axios.get(`${this.apiBaseUrl}/presences/${childId}`, {
          params: {
            year: now.getFullYear(),
            month: now.getMonth() + 1
          }
        })

        const selectedDays = response.data.selectedDays || []
        const totalHours = response.data.totalHours || 0

        this.monthlyStats = {
          totalDays: selectedDays.length,
          totalHours: totalHours,
          avgHours: selectedDays.length > 0 ? (totalHours / selectedDays.length).toFixed(1) : 0
        }
      } catch (error) {
        console.error('Erreur lors du chargement des statistiques:', error)
      }
    },

    showMessage(text, type = 'success') {
      this.message = text
      this.messageType = type
      setTimeout(() => {
        this.message = ''
      }, 5000)
    },

    getAge(birthDateString) {
      if (!birthDateString) return 0
      const birthDate = new Date(birthDateString)
      const today = new Date()
      let age = today.getFullYear() - birthDate.getFullYear()
      const monthDiff = today.getMonth() - birthDate.getMonth()
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--
      }
      return age
    }
  }
}
</script>


<style scoped>
.container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.page-title {
  text-align: center;
  color: #2c3e50;
  margin-bottom: 30px;
  font-size: 28px;
  font-weight: 600;
}

.child-selector {
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.form-label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #344a40;
}

.form-select {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 16px;
  background: white;
  transition: border-color 0.2s;
}

.form-select:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.alert {
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-weight: 500;
}

.alert.success {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.alert.error {
  background: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.presence-section {
  margin-bottom: 30px;
}

.child-info {
  text-align: center;
  margin-bottom: 20px;
  padding: 16px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 12px;
}

.child-info h2 {
  margin: 0 0 8px 0;
  font-size: 24px;
  font-weight: 600;
}

.child-age {
  margin: 0;
  font-size: 16px;
  opacity: 0.9;
}

.stats-section {
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.stats-section h3 {
  margin: 0 0 20px 0;
  color: #2c3e50;
  font-size: 20px;
  font-weight: 600;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 16px;
}

.stat-card {
  text-align: center;
  padding: 20px;
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  color: white;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.stat-number {
  font-size: 28px;
  font-weight: bold;
  margin-bottom: 8px;
}

.stat-label {
  font-size: 14px;
  opacity: 0.9;
  font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
  .container {
    padding: 16px;
  }
  
  .page-title {
    font-size: 24px;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>