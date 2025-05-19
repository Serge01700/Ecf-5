<template>
    <div class="app-layout">
      <aside class="sidebar">
        <div class="logo">Nursery Manager</div>
        <nav class="menu">
          <router-link to="/" exact active-class="active">
            <div class="menu-item">
              <img src="assets/img/icons8-home-100 (1).png" alt="Home">
              <span>Home</span>
            </div>
          </router-link>
          <router-link to="/espace-enfant" active-class="active">
            <div class="menu-item">
              <img src="assets/img/icons8-child-100.png" alt="Espace enfant">
              <span>Espace enfant</span>
            </div>
          </router-link>
          <router-link to="/espace-parent" active-class="active">
            <div class="menu-item">
              <img src="" alt="Espace parent">
              <span>Espace parent</span>
            </div>
          </router-link>
          <router-link to="/planning" active-class="active">
            <div class="menu-item">
              <img src="" alt="Planning">
              <span>Planning</span>
            </div>
          </router-link>
          <router-link to="/personnel" active-class="active">
            <div class="menu-item">
              <img src="" alt="Personnel">
              <span>Personnel</span>
            </div>
          </router-link>
          <router-link to="/parametre" active-class="active">
            <div class="menu-item">
              <img src="" alt="Paramètre">
              <span>Paramètre</span>
            </div>
          </router-link>
        </nav>
      </aside>
  
      <main class="main-container">
        <router-view></router-view>
      </main>
    </div>
  </template>
  
  <script setup>

  </script>
  
  <style scoped>
  .app-layout {
    display: flex;
    height: 100vh;
  }
  
  .sidebar {
    background-color: #222; /* Couleur de fond de la sidebar */
    color: #eee;
    width: 250px;
    padding: 20px;
    display: flex;
    flex-direction: column;
  }
  
  .logo {
    font-size: 1.5em;
    font-weight: bold;
    margin-bottom: 30px;
    text-align: center;
  }
  
  .menu {
    flex-grow: 1;
  }
  
  .menu-item {
    display: flex;
    align-items: center;
    padding: 10px;
    text-decoration: none;
    color: #eee;
    transition: background-color 0.3s ease;
    border-radius: 5px;
    margin-bottom: 5px;
  }
  
  .menu-item img {
    width: 20px;
    height: 20px;
    margin-right: 10px;
    filter: invert(1); /* Inverser la couleur pour que les icônes soient blanches */
  }
  
  .menu-item span {
    flex-grow: 1;
  }
  
  .menu-item.active,
  .menu-item:hover {
    background-color: #333;
  }
  
  .main-container {
    flex-grow: 1;
    background-color: #f4f4f4; /* Couleur de fond du conteneur principal (facultatif) */
    padding: 20px; /* Ajout de padding pour le contenu principal */
  }
  </style>