<template>
<!-- MODALE SUIVI ENFANT -->
<section v-if="showModalSuivi" class="modale-suivis">
    <h1>Suivi de l'enfant </h1>
    <p>Fiche enfant</p>
    <div class="grid-child">
        <figure>
            <img src="" alt="">
            <figcaption>Lea</figcaption>
        </figure>
        <div class="child-info">
            <h2>Nom</h2>
            <p>Née le 2 juin </p>
            <figure style="display:flex">
                <img src="" alt="groupe">
                <figcaption>Groupe petits</figcaption>
            </figure>
            <figure style="display:flex">
                <img src="" alt="Allergies">
                <figcaption>Allergies: arachides</figcaption>
            </figure>
            <figure style="display:flex">
                <img src="../assets/icons/icons8-add-male-user-group-100.png" alt="sieste">
                <figcaption>Deux siestes par jours</figcaption>
            </figure>
        </div>
    </div>
    <p>Repas:</p>
    <p>Cantine de la crèche</p>
    <div class="grid-child">
        <section class="sante">
            <h2>Suivis Santé</h2>
            <figure style="display:flex">
                <img src="" alt="Temperature">
                <figcaption>Temperature: 36.8 ° C</figcaption>
            </figure>
        </section>
        <section class="incident">
            <h2>Incidents</h2>
            <figure style="display:flex">
                <img src="" alt="Incidents">
                <figcaption>Aucun incidents</figcaption>
            </figure>
        </section>
        <section class="responsable">
            <h2>Responsables légaux</h2>
            <p>Lea</p>
        </section>
    </div>
    
</section>

</template>

<script setup>

</script>

<style scopped>

</style>