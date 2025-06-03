<template>
<section class="child-form">
    <div class="img-child"></div>
    <div class="flex">
        <p class="username">Nom</p>
        <p class="name">Prenom</p>
        
    </div>
    <p class="age">2 ans </p>
    <p class="allergie">Allergie</p>
    <div class="flex">
        <button id="more">Voir</button>
    </div>
        
</section>
</template>



<script setup>



</script>



<style scopped>

.child-form{
    background-color: white;
    border-radius: 8px;
    padding: 19px;
     max-width: 420px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.img-child{
    margin: 0 auto;
    height: 80px;
    width: 50px;
    background-color: rgb(231, 231, 231);
    border-radius: 50%;
}

div.flex{
    display: flex;
    justify-content: space-evenly;
}

p.age,
p.allergie{
    text-align: center;
    margin: 10px 0;
}

button#more {
    background-color: royalblue;
    border: none;
    padding: 5px;
    margin-top: 10px;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    margin: 0 auto;
}

</style>