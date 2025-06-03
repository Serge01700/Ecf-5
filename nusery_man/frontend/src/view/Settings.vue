<template>
  <div class="settings-page">
    <div class="background-animation">
      <div class="floating-shape shape-1"></div>
      <div class="floating-shape shape-2"></div>
      <div class="floating-shape shape-3"></div>
      <div class="floating-shape shape-4"></div>
    </div>

    <header class="settings-header">
      <div class="user-avatar-header">
        <svg viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
      </div>
    </header>

    <div class="settings-container">
      <!-- Sidebar Navigation -->
      <aside class="settings-sidebar glass">
        <h2 class="sidebar-title">Paramètres</h2>
        <nav class="sidebar-nav">
          <button 
            v-for="tab in tabs" 
            :key="tab.id"
            class="nav-item"
            :class="{ active: activeTab === tab.id }"
            @click="activeTab = tab.id"
          >
            <component :is="tab.icon" class="nav-icon" />
            {{ tab.label }}
          </button>
        </nav>
      </aside>
  
      <main class="settings-main">
       
        <div v-if="activeTab === 'compte'" class="settings-content">
          <div class="content-header glass">
            <h1>Compte</h1>
          </div>

          <!-- Profile Section -->
          <section class="profile-section glass">
            <div class="profile-avatar-section">
              <div class="profile-avatar">
                <div class="avatar-placeholder"></div>
              </div>
              <div class="profile-info">
                <h3>{{ userProfile.name }}</h3>
                <span class="user-badge">Admin</span>
              </div>
            </div>
          </section>

          
          <section class="form-section glass">
            <div class="form-row">
              <div class="form-group">
                <label>Name</label>
                <input 
                  v-model="userProfile.name" 
                  type="text" 
                  class="glass-input"
                  placeholder="Votre nom"
                >
              </div>
              <div class="form-group">
                <label>Email</label>
                <input 
                  v-model="userProfile.email" 
                  type="email" 
                  class="glass-input"
                  placeholder="votre.email@example.com"
                >
              </div>
            </div>
          </section>

          <!-- Password Change Section -->
          <section class="password-section glass">
            <button class="password-change-btn" @click="showPasswordModal = true">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z"/>
              </svg>
              <div class="btn-content">
                <span class="btn-title">Changez votre mot de passe</span>
              </div>
            </button>
          </section>

          <!-- Delete Account Section -->
          <section class="danger-section glass">
            <button class="delete-account-btn" @click="showDeleteModal = true">
              <span class="btn-title">Supprimer le compte</span>
              <span class="btn-subtitle">Contactez notre support pour supprimer votre compte</span>
            </button>
          </section>
        </div>

        <!-- Enfants Tab -->
        <div v-if="activeTab === 'enfants'" class="settings-content">
          <div class="content-header glass">
            <h1>Gestion des Enfants</h1>
          </div>
          
          <section class="children-list glass">
            <div v-for="child in children" :key="child.id" class="child-item">
              <div class="child-info">
                <h4>{{ child.name }}</h4>
                <span class="child-age">{{ child.age }} ans</span>
              </div>
              <button class="edit-child-btn">Modifier</button>
            </div>
            <button class="add-child-btn">+ Ajouter un enfant</button>
          </section>
        </div>

       
      </main>
    </div>

    <!-- Password Modal -->
    <div v-if="showPasswordModal" class="modal-overlay" @click="closePasswordModal">
      <div class="modal-content glass" @click.stop>
        <h3>Changer le mot de passe</h3>
        <form @submit.prevent="changePassword">
          <div class="form-group">
            <label>Mot de passe actuel</label>
            <input v-model="passwordForm.current" type="password" class="glass-input" required>
          </div>
          <div class="form-group">
            <label>Nouveau mot de passe</label>
            <input v-model="passwordForm.new" type="password" class="glass-input" required>
          </div>
          <div class="form-group">
            <label>Confirmer le nouveau mot de passe</label>
            <input v-model="passwordForm.confirm" type="password" class="glass-input" required>
          </div>
          <div class="modal-actions">
            <button type="button" @click="closePasswordModal" class="cancel-btn">Annuler</button>
            <button type="submit" class="submit-btn">Changer</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Account Modal -->
    <div v-if="showDeleteModal" class="modal-overlay" @click="closeDeleteModal">
      <div class="modal-content glass" @click.stop>
        <h3>Supprimer le compte</h3>
        <p>Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>
        <div class="modal-actions">
          <button @click="closeDeleteModal" class="cancel-btn">Annuler</button>
          <button @click="deleteAccount" class="delete-btn">Supprimer</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'

// État réactif
const activeTab = ref('compte')
const showPasswordModal = ref(false)
const showDeleteModal = ref(false)
const selectedTheme = ref('default')

// Données utilisateur
const userProfile = reactive({
  name: 'GRIGORIAN Serge',
  email: 'serge.grigorian69@gmail.com'
})

// Formulaire de mot de passe
const passwordForm = reactive({
  current: '',
  new: '',
  confirm: ''
})

// Navigation tabs
const tabs = [
  { id: 'compte', label: 'Compte', icon: 'UserIcon' },
  { id: 'enfants', label: 'Enfants', icon: 'ChildIcon' },

]

// Données enfants
const children = ref([
  { id: 1, name: 'Nina Duval', age: 2 },
  { id: 2, name: 'Paul Martin', age: 3 }
])

// Thèmes
const themes = [
  { id: 'default', name: 'Défaut', color: '#6366f1' },
  { id: 'pink', name: 'Rose', color: '#ec4899' },
  { id: 'green', name: 'Vert', color: '#10b981' },
  { id: 'orange', name: 'Orange', color: '#f59e0b' }
]

// Méthodes
const changePassword = () => {
  if (passwordForm.new !== passwordForm.confirm) {
    alert('Les mots de passe ne correspondent pas')
    return
  }
  
  console.log('Changement de mot de passe...')
  closePasswordModal()
}

const closePasswordModal = () => {
  showPasswordModal.value = false
  Object.assign(passwordForm, { current: '', new: '', confirm: '' })
}

const deleteAccount = () => {
  console.log('Suppression du compte...')
  closeDeleteModal()
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
}
</script>

<style scoped>

:global(.main-container.no-padding) {
  padding: 0 !important;
}


.settings-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #eff2ff 0%, #5d5d5d 100%);
  position: relative;
  overflow: hidden;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Background Animation */
.background-animation {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  overflow: hidden;
}

.floating-shape {
  position: absolute;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  animation: float 3s ease-in-out infinite;
}

.shape-1 {
  width: 80px;
  height: 80px;
  top: 20%;
  left: 10%;
  animation-delay: 0s;
}

.shape-2 {
  width: 120px;
  height: 120px;
  top: 60%;
  right: 10%;
  animation-delay: 2s;
}

.shape-3 {
  width: 60px;
  height: 60px;
  top: 80%;
  left: 20%;
  animation-delay: 4s;
}

.shape-4 {
  width: 100px;
  height: 100px;
  top: 10%;
  right: 30%;
  animation-delay: 0.7s;
}

@keyframes float {
  0%, 100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-20px) rotate(180deg);
  }
}


.glass {
  background: rgba(14, 10, 10, 0.15);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

/* Header */
.settings-header {
  position: absolute;
  top: 20px;
  right: 20px;
  z-index: 10;
}

.user-avatar-header {
  width: 48px;
  height: 48px;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(20px);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.user-avatar-header svg {
  width: 24px;
  height: 24px;
}


/* Main Container */
.settings-container {
  display: flex;
  min-height: 100vh;
  padding: 20px;
  gap: 20px;
}

/* Sidebar */
.settings-sidebar {
  width: 280px;
  padding: 24px;
  flex-shrink: 0;
}

.sidebar-title {
  color: white;
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 24px;
  text-align: center;
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 12px;
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 16px;
}

.nav-item:hover,
.nav-item.active {
  background: rgba(255, 255, 255, 0.25);
  border-color: rgba(255, 255, 255, 0.4);
  transform: translateY(-2px);
}

.nav-icon {
  width: 20px;
  height: 20px;
}

/* Main Content */
.settings-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.settings-content {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.content-header {
  padding: 24px;
}

.content-header h1 {
  color: white;
  font-size: 32px;
  font-weight: 700;
  margin: 0;
}


.profile-section {
  padding: 24px;
}

.profile-avatar-section {
  display: flex;
  align-items: center;
  gap: 20px;
}

.profile-avatar {
  position: relative;
}

.avatar-placeholder {
  width: 80px;
  height: 80px;
  background: rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border: 2px solid rgba(255, 255, 255, 0.4);
}

.profile-info h3 {
  color: white;
  font-size: 24px;
  font-weight: 600;
  margin: 0 0 8px 0;
}

.user-badge {
  background: #3b82f6;
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.form-section {
  padding: 24px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group label {
  color: white;
  font-weight: 500;
  font-size: 14px;
}

.glass-input {
  padding: 16px;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 12px;
  color: white;
  font-size: 16px;
  backdrop-filter: blur(10px);
}

.glass-input::placeholder {
  color: rgba(255, 255, 255, 0.6);
}

.glass-input:focus {
  outline: none;
  border-color: rgba(255, 255, 255, 0.5);
  background: rgba(255, 255, 255, 0.15);
}

/* Password Section */
.password-section {
  padding: 24px;
}

.password-change-btn {
  display: flex;
  align-items: center;
  gap: 16px;
  width: 100%;
  padding: 20px;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 12px;
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
}

.password-change-btn:hover {
  background: rgba(255, 255, 255, 0.15);
  transform: translateY(-2px);
}

.password-change-btn svg {
  width: 24px;
  height: 24px;
}

.btn-content {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.btn-title {
  font-size: 16px;
  font-weight: 500;
}


.danger-section {
  padding: 24px;
}

.delete-account-btn {
  width: 100%;
  padding: 20px;
  background: rgba(239, 68, 68, 0.2);
  border: 1px solid rgba(239, 68, 68, 0.4);
  border-radius: 12px;
  color: #fecaca;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: left;
}

.delete-account-btn:hover {
  background: rgba(239, 68, 68, 0.3);
  transform: translateY(-2px);
}

.btn-subtitle {
  display: block;
  font-size: 14px;
  color: rgba(254, 202, 202, 0.8);
  margin-top: 4px;
}

/* Children Section */
.children-list {
  padding: 24px;
}

.child-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px;
  margin-bottom: 12px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.child-info h4 {
  color: white;
  margin: 0 0 4px 0;
  font-size: 16px;
}

.child-age {
  color: rgba(255, 255, 255, 0.7);
  font-size: 14px;
}

.edit-child-btn,
.add-child-btn {
  padding: 8px 16px;
  background: rgba(59, 130, 246, 0.3);
  border: 1px solid rgba(59, 130, 246, 0.5);
  border-radius: 8px;
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
}

.edit-child-btn:hover,
.add-child-btn:hover {
  background: rgba(59, 130, 246, 0.4);
}

.add-child-btn {
  width: 100%;
  padding: 16px;
  margin-top: 16px;
}


.theme-section {
  padding: 24px;
}

.theme-section h3 {
  color: white;
  font-size: 20px;
  margin-bottom: 16px;
}

.theme-options {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 16px;
}

.theme-option {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  padding: 16px;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 12px;
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
}

.theme-option:hover,
.theme-option.active {
  background: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.4);
}

.theme-preview {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 2px solid rgba(255, 255, 255, 0.3);
}


.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(10px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  max-width: 500px;
  width: 90%;
  padding: 32px;
  color: white;
}

.modal-content h3 {
  margin-bottom: 24px;
  font-size: 24px;
}

.modal-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  margin-top: 24px;
}

.cancel-btn,
.submit-btn,
.delete-btn {
  padding: 12px 24px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}

.cancel-btn {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: white;
}

.submit-btn {
  background: #3b82f6;
  border: none;
  color: white;
}

.delete-btn {
  background: #ef4444;
  border: none;
  color: white;
}

.cancel-btn:hover,
.submit-btn:hover,
.delete-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Responsive */
@media (max-width: 768px) {
  .settings-container {
    flex-direction: column;
    padding: 10px;
  }
  
  .settings-sidebar {
    width: 100%;
  }
  
  .form-row {
    grid-template-columns: 1fr;
  }
  
  .theme-options {
    grid-template-columns: repeat(2, 1fr);
  }
}


</style>