<template>
  <f7-page name="home" ptr :ptr-mousewheel="true" @ptr:refresh="refresh">
    <!-- Top Navbar -->
    <f7-navbar :sliding="false">
      <f7-nav-left>
        <f7-link panel-open="left">
        <img v-if="user" class="message-avatar" :src="user.image_url">
        </f7-link>
      </f7-nav-left>

      <f7-nav-title sliding>ChefPilot</f7-nav-title>
      <f7-nav-title-large> 👋🏻 {{ user?.name ? "Hi " + user?.name + "," : "" }}</f7-nav-title-large>
      <f7-nav-right>
        <f7-link href="#">
          <f7-icon
              ios="f7:bell"
              md="f7:bell"
              f7="bell"
          >
            <f7-badge color="red">5</f7-badge>
          </f7-icon>
        </f7-link>
      </f7-nav-right>
    </f7-navbar>


    <f7-block-title style="margin-top: 0!important;">Your Food Inventory</f7-block-title>

    <f7-block strong inset style="background-color: #00FF9F62">

      <added-recipe-card></added-recipe-card>
    </f7-block>

    <recipes></recipes>

    <recent-bookmarks></recent-bookmarks>

  </f7-page>
</template>
<script setup>
import AddedRecipeCard from "@/components/home/AddedRecipeCard.vue";
import Recipes from "@/components/recipe/Recipes.vue";
import {f7, useStore} from "framework7-vue";
import store from "@/js/store";
import {ref} from "vue";
import RecentBookmarks from "@/components/recipe/recentBookmarks.vue";

const user = useStore(store, "getUser")

const showRefresh = ref(useStore(store,"getRefresh"));

const image_url = user?.user ? user.user.image_url : '/img/dp.png';
const refresh = (done) => {

  const newState = !showRefresh.value;

  store.dispatch("changeRefreshState",newState)
  done();

}
</script>