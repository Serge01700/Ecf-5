<template>
    <div class="app-layout">
      <aside class="sidebar">
        <div class="logo">Nursery Manager</div>
        <nav class="menu">
          <router-link to="/" active-class="active">
            <div class="menu-item">
             <p>{{ homeIcon }} </p>
              <img :src="homeIcon" alt="Home">
              <span>Accueil</span>
            </div>
          </router-link>
          <router-link to="/child" active-class="active">
            <div class="menu-item">
              <img :src="childIcon" alt="Children">
              <span>Espace enfant</span>
            </div>
          </router-link>
          <router-link to="/parent" active-class="active">
            <div class="menu-item">
              <img :src="parentIcon" alt="Parents">
              <span>Espace Parent</span>
            </div>
          </router-link>
          <router-link to="/schedule" active-class="active">
            <div class="menu-item">
              <img :src="scheduleIcon" alt="Schedule">
              <span>Planning</span>
            </div>
          </router-link>
          <router-link to="/staff" active-class="active">
            <div class="menu-item">
              <img :src="staffIcon" alt="Staff">
              <span>Personnel</span>
            </div>
          </router-link>
          <router-link to="/settings" active-class="active">
            <div class="menu-item">
              <img :src="settingsIcon" alt="Settings">
              <span>Paramètres</span>
            </div>
          </router-link>
        </nav>
      </aside>
      <main class="main-container" :class="{ 'no-padding': $route.name === 'settings' }">
        <router-view></router-view>
      </main>
    </div>
</template>

<script setup>
import homeIcon from '@/assets/icons/icons8-home-100 (1).png'
import childIcon from '@/assets/icons/icons8-child-100.png'
import parentIcon from '@/assets/icons/icons8-parent-96.png'
import scheduleIcon from '@/assets/icons/icons8-schedule-100.png'
import staffIcon from '@/assets/icons/icons8-staff-100.png'
import settingsIcon from '@/assets/icons/icons8-settings-100.png'
</script>

<style scoped>
.app-layout {
  display: flex;
  min-height: 100vh;
}

.sidebar {
  position: fixed;
  height: 100vh;
  width: 250px;
  background-color: #202020;
  color: white;
  padding: 1rem;
  left: 0;
  top: 0;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 2rem;
  text-align: center;
}

.menu {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.menu a {
  text-decoration: none;
  color: white;
}

.menu-item {
  display: flex;
  align-items: center;
  padding: 0.5rem;
  border-radius: 8px;
  transition: background-color 0.3s;
}

.menu-item:hover {
  background-color: #34495e;
}

.menu-item img {
  width: 24px;
  height: 24px;
  margin-right: 1rem;
}

.menu-item span {
  font-size: 1rem;
}

.active .menu-item {
  background-color: #3498db;
}

.main-container {
  flex: 1;
  padding: 2rem;
  background-color: #f5f6fa;
  margin-left: 250px; 
}
</style>