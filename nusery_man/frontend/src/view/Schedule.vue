<template>
<!-- MODALE SUIVI ENFANT -->
<section class="modale-suivis">
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
    <section class="sante">
        <h2>Suivis Santé</h2>
        <figure style="display:flex">
            <img src="" alt="Temperature">
            <figcaption>Temperature: 36.8 ° C</figcaption>
        </figure>
    </section>

</section>

</template>

<script setup>

</script>

<style scopped>
section.modale-suivis > div.grid-child {
    display: grid;
    grid-template-columns: 1fr 1fr;
}
</style>