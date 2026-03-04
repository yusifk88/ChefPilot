<template>
  <f7-block-title>

    <div class="grid grid-cols-2 grid-gap">
      <div style="margin-top: 8px">Dishes of the day</div>
      <div>
        <f7-button :disabled="loadingRecipes" @click="showSheet=true" fill>Get Recipes &nbsp;<f7-badge color="red">+{{recipeLimit}}</f7-badge></f7-button>
      </div>
    </div>


  </f7-block-title>

  <recipe-loading v-if="loadingRecipes"></recipe-loading>
  <f7-block inset strong v-else>

    <f7-card
        v-if="loading"
        class="skeleton-text no-margin skeleton-effect-pulse"
        title="Card Header"
        content="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit."
        footer="Card Footer"
        style="height: 400px"
    ></f7-card>


    <swiper-container
        :pagination="false"
        class="no-padding no-margin"
        :space-between="15"
        :speed="900"
        v-else-if="items && items.length>0"
    >
      <swiper-slide class=" no-padding"
                    v-for="(item,index) in items" :key="item.id">
        <recipe-item :item="item"></recipe-item>
      </swiper-slide>

    </swiper-container>

    <span v-else>

    <empty-state

        type="recipes"
        title="No dishes or recipes yet"
        details="Recipes suggested by chefpilot using your food inventory will show here"
    ></empty-state>
    </span>

  </f7-block>

  <f7-sheet
      swipe-to-close
      style="height: 60%"
      v-model:opened="showSheet"
      class="user-items-sheet"
  >
    <div class="swipe-handler" style="background-color: transparent"></div>
    <f7-toolbar
        style="height: 65px"
    >
      <div class="left"></div>

      <div class="right">
        <f7-link sheet-close><i class="icon icon-close"></i></f7-link>
      </div>
    </f7-toolbar>

    <f7-page-content>

    <f7-block  class="margin-top">
        <vue3-lottie width="300px" :animation-data="chef"></vue3-lottie>
        <h3 style="text-align: center" class="no-padding no-margin">
          <strong>Confirm your food pantry</strong>
        </h3>
        <p style="text-align: center">
          To get accurate recipes make sure to update your food pantry before you continue.
        </p>
        <f7-button @click="requestRecipes(); showSheet=false;" large class="margin-top" fill  block>Request Recipes</f7-button>
        <f7-button @click="showSheet=false" large fill class="margin-top" color="red"  block>Cancel</f7-button>
    </f7-block>
    </f7-page-content>
  </f7-sheet>

</template>

<script>
import RecipeItem from "@/components/recipe/RecipeItem.vue";
import {f7, useStore} from "framework7-vue";
import store from "@/js/store";
import EmptyState from "@/components/empty/EmptyState.vue";
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";
import {liveQuery} from "dexie";
import {useObservable} from "@vueuse/rxjs";
import {db} from "@/js/db";
import RecipeLoading from "@/components/recipe/RecipeLoading.vue";
import axios from "axios";
import {AUTH_HEADERS} from "@/js/utility";
import {Vue3Lottie} from "vue3-lottie";

import chef from "@/js/animation/chef.json";

export default {
  name: "Recipes",
  components: {Vue3Lottie, RecipeLoading, EmptyState, RecipeItem},
  data() {
    return {
      items: useObservable(liveQuery(() => db.recipes.orderBy("id").reverse().toArray())),
      shouldRefresh: useStore(store, "getRefresh"),
      loading: false,
      loadingRecipes: useStore(store, "loadingRecipesState"),
      currentUser: useStore(store, "getUser"),
      recipeLimit: 0,
      showSheet: false,
      chef: chef
    }
  },

  computed: {

    startOfDay() {
      const startOfToday = new Date();
      startOfToday.setHours(0, 0, 0, 0);

      return startOfToday;
    },
    endOfDay() {
      const endOfToday = new Date();
      endOfToday.setHours(23, 59, 59, 999);
      return endOfToday;
    },

    currentUser() {
      return useStore(store, "getUser");
    }
  },
  watch: {

    shouldRefresh() {
      this.getItems();

    }
  },
  methods: {

    async getLimit() {


      axios.get("/gen-recipes-count", AUTH_HEADERS)
          .then(res => {
            this.recipeLimit = res.data.data.count;
          })
          .catch(error => {
          })
    },
    requestRecipes() {
      store.dispatch("startLoadingRecipe");

      axios.get("/gen-recipes")
          .then(res => {

          })
          .catch(error => {
            store.dispatch("stopLoadingRecipe");

          })

    },

    async getItems() {

      this.loading = true;

      const count = await db.recipes.count();

      this.loading = count === 0;

      if (!this.loading) {

        f7.progressbar.show('multi');

      }


      axios.get("/recipes", AUTH_HEADERS)
          .then(res => {
            const items = res.data.data;

            db.recipes.bulkPut(items)

            this.loading = false;

            f7.progressbar.hide()

            store.dispatch("stopLoadingRecipe");


          })
    },

    listenForEvents() {

      const drone = new Scaledrone('Yh4KOdyE8eyesTXu');

      //const account = await CapacitorPersistentAccount.readAccount()

      //if (account.data && account.data.user) {

      //alert("hello test")
      const room = drone.subscribe('RecipeCreated_' + this.currentUser.id);

      room.on('message', message => {

        store.dispatch("stopLoadingRecipe");

        db.recipes.bulkPut([message.data])

        this.getLimit();
      });

      // }


    }

  },

  mounted() {

    this.getLimit();
    this.getItems();
    this.listenForEvents();

  }
}
</script>


<style scoped>

</style>