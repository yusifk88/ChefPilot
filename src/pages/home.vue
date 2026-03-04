<template>
  <f7-page name="home" ptr :ptr-mousewheel="true" @ptr:refresh="refresh">
    <!-- Top Navbar -->
    <f7-navbar :sliding="false">
      <f7-nav-left>
        <f7-link panel-open="left">
        <img style="margin-bottom: 7px" v-if="user" class="message-avatar" :src="user.image_url">
        </f7-link>
      </f7-nav-left>

      <f7-nav-title sliding>ChefPilot</f7-nav-title>
      <f7-nav-title-large> 👋🏻 {{ user?.name ? "Hi " + user?.name.split(" ")[0] + "," : "" }}</f7-nav-title-large>
      <f7-nav-right>
    <notification-bell-button></notification-bell-button>
      </f7-nav-right>
    </f7-navbar>

    <f7-block-title style="margin-top: 0!important;">Your Food Inventory</f7-block-title>

    <f7-block strong inset style="background-color: #00FF9F62">

      <added-recipe-card v-if="user"></added-recipe-card>
    </f7-block>

    <recipes v-if="user"></recipes>

    <recent-bookmarks></recent-bookmarks>

  </f7-page>
</template>
<script>
import AddedRecipeCard from "@/components/home/AddedRecipeCard.vue";
import Recipes from "@/components/recipe/Recipes.vue";
import {f7, useStore} from "framework7-vue";
import store from "@/js/store";
import RecentBookmarks from "@/components/recipe/recentBookmarks.vue";
import NotificationBellButton from "@/components/notifications/NotificationBellButton.vue";

export default {
  components:{
    NotificationBellButton,
    RecentBookmarks,AddedRecipeCard,Recipes
  },
  data(){
    return{
      user:useStore(store, "getUser"),
      showRefresh:useStore(store,"getRefresh"),
      unreadNotifications:useStore(store,"getUnreadNotificationsCount")
    }
  },
  methods:{


    refresh(done){
      const newState = !this.showRefresh;
      store.dispatch("changeRefreshState",newState)
      done();
    }
  }

}


</script>