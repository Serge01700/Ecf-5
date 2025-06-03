<template>
  <div class="messagerie-container">
    <!-- En-tête -->
    <div class="header">
      <div class="header-content">
        <div class="header-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z" fill="currentColor"/>
          </svg>
        </div>
        <h2>Messages récents des parents</h2>
      </div>
      <button v-if="isAdmin" @click="rafraichir" class="btn-refresh">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4C7.58 4 4 7.58 4 12S7.58 20 12 20C15.73 20 18.84 17.45 19.73 14H17.65C16.83 16.33 14.61 18 12 18C8.69 18 6 15.31 6 12S8.69 6 12 6C13.66 6 15.14 6.69 16.22 7.78L13 11H20V4L17.65 6.35Z" fill="currentColor"/>
        </svg>
      </button>
    </div>

    <!-- Liste des messages -->
    <div class="messages-list">
      <div v-if="loading" class="loading">
        Chargement des messages...
      </div>
      
      <div v-else-if="messages.length === 0" class="no-messages">
        Aucun message pour le moment
      </div>
      
      <div v-else>
        <div 
          v-for="message in messages" 
          :key="message.id"
          class="message-item"
          :class="{ 'non-lu': !message.lu && isAdmin }"
          @click="marquerComeLu(message)"
        >
          <div class="message-header">
            <div class="expediteur">
              <div class="expediteur-info">
                <span class="nom">{{ message.expediteur }}</span>
                <span class="type" :class="message.typeExpediteur">
                  {{ message.typeExpediteur === 'parent' ? 'Parent' : 'Admin' }}
                </span>
              </div>
              <div class="heure">{{ message.dateEnvoi }}</div>
            </div>
            <div v-if="!message.lu && isAdmin" class="indicateur-non-lu"></div>
          </div>
          
          <div class="message-contenu">
            {{ message.contenu }}
          </div>
          
          <div v-if="isAdmin" class="message-actions">
            <button @click.stop="supprimerMessage(message.id)" class="btn-delete">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V7H6V19ZM19 4H15.5L14.5 3H9.5L8.5 4H5V6H19V4Z" fill="currentColor"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Messagerie',
  data() {
    return {
      messages: [],
      loading: false,
      isAdmin: false,
      nouveauMessage: {
        expediteur: '',
        contenu: ''
      },
      envoyeEnCours: false
    }
  },
  
  mounted() {
    this.chargerMessages()
    this.verifierRoleUtilisateur()
  },
  
  methods: {
    async chargerMessages() {
      this.loading = true
      try {
        const response = await axios.get('/api/messages')
        this.messages = response.data
      } catch (error) {
        console.error('Erreur lors du chargement des messages:', error)
        alert('Erreur lors du chargement des messages')
      } finally {
        this.loading = false
      }
    },
    
    async envoyerMessage() {
      if (!this.nouveauMessage.contenu.trim()) return
      
      this.envoyeEnCours = true
      try {
        const response = await axios.post('/api/messages', this.nouveauMessage)
        
        // Ajouter le nouveau message en haut de la liste
        this.messages.unshift(response.data)
        
        // Réinitialiser le formulaire
        this.nouveauMessage = {
          expediteur: this.nouveauMessage.expediteur, // Garder le nom
          contenu: ''
        }
        
        alert('Message envoyé avec succès!')
      } catch (error) {
        console.error('Erreur lors de l\'envoi:', error)
        alert('Erreur lors de l\'envoi du message')
      } finally {
        this.envoyeEnCours = false
      }
    },
    
    async marquerComeLu(message) {
      if (!this.isAdmin || message.lu) return
      
      try {
        await axios.put(`/api/messages/${message.id}/marquer-lu`)
        message.lu = true
      } catch (error) {
        console.error('Erreur lors du marquage:', error)
      }
    },
    
    async supprimerMessage(messageId) {
      if (!confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) return
      
      try {
        await axios.delete(`/api/messages/${messageId}`)
        this.messages = this.messages.filter(m => m.id !== messageId)
      } catch (error) {
        console.error('Erreur lors de la suppression:', error)
        alert('Erreur lors de la suppression')
      }
    },
    
    rafraichir() {
      this.chargerMessages()
    },
    
    verifierRoleUtilisateur() {
      
      this.isAdmin = window.userRole === 'ROLE_ADMIN'
    }
  }
}
</script>

<style scoped>
.messagerie-container {
  max-width: 800px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  margin: 0 auto;
  border-radius: 8px;
  background-color: white;
  padding: 20px;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  padding-bottom: 16px;
  border-bottom: 1px solid #e1e5e9;
}

.header-content {
  display: flex;
  align-items: center;
  gap: 12px;
}

.header-icon {
  color: #666;
}

.header h2 {
  margin: 0;
  color: #333;
  font-size: 1.1rem;
  font-weight: 600;
}

.btn-refresh {
  background: none;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 8px;
  cursor: pointer;
  color: #666;
  transition: all 0.2s;
}

.btn-refresh:hover {
  background-color: #f5f5f5;
  color: #333;
}

.messages-list {
  margin-bottom: 32px;
}

.loading, .no-messages {
  text-align: center;
  padding: 40px;
  color: #666;
  font-style: italic;
}

.message-item {
  background: white;
  border: 1px solid #e1e5e9;
  border-radius: 12px;
  padding: 16px;
  margin-bottom: 12px;
  transition: all 0.2s;
  position: relative;
}

.message-item:hover {
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.message-item.non-lu {
  border-left: 4px solid #007bff;
  background: #f8f9ff;
}

.message-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.expediteur {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.expediteur-info {
  display: flex;
  align-items: center;
  gap: 8px;
}

.nom {
  font-weight: 600;
  color: #333;
}

.type {
  font-size: 0.75rem;
  padding: 2px 8px;
  border-radius: 12px;
  text-transform: uppercase;
  font-weight: 500;
}

.type.parent {
  background: #e3f2fd;
  color: #1976d2;
}

.type.admin {
  background: #f3e5f5;
  color: #7b1fa2;
}

.heure {
  font-size: 0.875rem;
  color: #666;
}

.indicateur-non-lu {
  width: 8px;
  height: 8px;
  background: #007bff;
  border-radius: 50%;
  position: absolute;
  top: 12px;
  right: 12px;
}

.message-contenu {
  color: #555;
  line-height: 1.5;
  margin-bottom: 12px;
}

.message-actions {
  display: flex;
  justify-content: flex-end;
}

.btn-delete {
  background: none;
  border: none;
  color: #dc3545;
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.btn-delete:hover {
  background-color: #fee;
}

.nouveau-message {
  background: white;
  border: 1px solid #e1e5e9;
  border-radius: 12px;
  padding: 24px;
}

.form-group {
  margin-bottom: 16px;
}

.form-group label {
  display: block;
  margin-bottom: 6px;
  font-weight: 500;
  color: #333;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 14px;
  transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.btn-envoyer {
  background: #007bff;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-envoyer:hover:not(:disabled) {
  background: #0056b3;
}

.btn-envoyer:disabled {
  background: #ccc;
  cursor: not-allowed;
}
</style>