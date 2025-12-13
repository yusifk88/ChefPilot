<template>
  <f7-page name="home">
    <!-- Top Navbar -->
    <f7-navbar :sliding="false">
      <f7-nav-left>
        <f7-link panel-open="left">
          <div class="message-avatar" style="background-image:url(/img/dp.png)"></div>
        </f7-link>
      </f7-nav-left>
      <f7-nav-title sliding>ChefPilot</f7-nav-title>
      <f7-nav-title-large>ChefPilot</f7-nav-title-large>

    </f7-navbar>

    <f7-block strong inset style="background-color: #00FF9F62">

      <added-recipe-card></added-recipe-card>

    </f7-block>
  </f7-page>
</template>
<script setup>
import AddedRecipeCard from "@/components/home/AddedRecipeCard.vue";
</script>