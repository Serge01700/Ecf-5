<template>
    <div class="app-layout">
      <aside class="sidebar">
        <div class="logo">Nursery Manager</div>
        <nav class="menu">
          <router-link to="/" exact active-class="active">
            <div class="menu-item">
              <img src="/img/icons8-home-100 (1).png" alt="Home">
              <span>Home</span>
            </div>
          </router-link>
          <router-link to="/" active-class="active">
            <div class="menu-item">
              <img src="/img/icons8-child-100.png" alt="">
              <span>Espace enfant</span>
            </div>
          </router-link>
          <router-link to="/" active-class="active">
            <div class="menu-item">
              <img src="/img/icons8-parent-96.png" alt="Espace parent">
              <span>Espace parent</span>
            </div>
          </router-link>
          <router-link to="/" active-class="active">
            <div class="menu-item">
              <img src="/img/icons8-schedule-100.png" alt="Planning">
              <span>Planning</span>
            </div>
          </router-link>
          <router-link to="/" active-class="active">
            <div class="menu-item">
              <img src="/img/icons8-staff-100.png" alt="Personnel">
              <span>Personnel</span>
            </div>
          </router-link>
          <router-link to="/" active-class="active">
            <div class="menu-item">
              <img src="/img/icons8-settings-100.png" alt="Paramètre">
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


body{
  margin: 0;
  padding: 0;
}
  
  .app-layout {
    display: flex;
    background-color: #202020;
    width: 100vw;
    height: 100vh;
  }
  
  .sidebar {
    height: 100vh;
    color: #eee;
    width: 200px;
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
    cursor: pointer;
    align-items: center;
    padding: 15px;
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
    filter: invert(1); 
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
    background-color:#F5F5F5;
    padding: 20px; 
    margin-top: 1.5rem;
    border: 1px solid #DBDBDB;
    border-top-left-radius: 9px;
    height: 95%;
  }
  </style>